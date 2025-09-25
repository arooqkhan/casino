<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\TransactionHistory;
use App\Models\User;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret');
        // $secret = "we_1S9OEiKrxuMCwUpjeoDKWmti";

        Log::error("Stripe Webhook sec:", $secret);
        // 🔥 Always log the raw payload for debugging
        Log::info("Stripe Webhook received", [
            'payload' => $payload,
            'headers' => $request->headers->all(),
        ]);
        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\UnexpectedValueException $e) {
            Log::error("Stripe Webhook error: Invalid payload");
            return response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error("Stripe Webhook error: Invalid signature");
            return response('Invalid signature', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            $userId = $session->metadata->user_id ?? null;
            $amount = $session->amount_total / 100;
            $paymentIntentId = $session->payment_intent;

            if ($userId && $paymentIntentId) {
                $user = User::find($userId);

                if ($user) {
                    // ✅ Avoid duplicate transaction using payment_intent
                    $exists = TransactionHistory::where('user_id', $user->id)
                        ->where('type', 'deposit')
                        ->where('trans_type', 'stripe')
                        ->where('reference', $paymentIntentId) // <-- store unique intent
                        ->exists();

                    if (!$exists) {
                        // Update balance
                        $user->balance += $amount;
                        $user->save();

                        // Create transaction
                        TransactionHistory::create([
                            'user_id'        => $user->id,
                            'type'           => 'deposit',
                            'amount'         => $amount,
                            'status'         => 1,
                            'is_sent'        => 0,
                            'trans_type'     => 'stripe',
                            'payment_status' => 'approved',
                            'reference'      => $paymentIntentId, // <-- unique
                        ]);
                    }
                }
            }
        }


        return response('Webhook handled', 200);
    }
}
