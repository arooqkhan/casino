<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\TransactionHistory;
use App\Models\User;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    // public function handle(Request $request)
    // {
    //     $payload = $request->getContent();
    //     $sigHeader = $request->header('Stripe-Signature');
    //     $secret = config('services.stripe.webhook_secret');
    //     // $secret = "we_1S9OEiKrxuMCwUpjeoDKWmti";

    //     // Log incoming request
    //     Log::info("Stripe Webhook received", [
    //         'signature' => $sigHeader,
    //         'payload'   => $payload,
    //     ]);

    //     // Debug secret (only while testing!)
    //     Log::debug("Stripe Webhook secret used", ['secret' => $secret]);
    //     try {
    //         $event = Webhook::constructEvent($payload, $sigHeader, $secret);
    //     } catch (\UnexpectedValueException $e) {
    //         Log::error("Stripe Webhook error: Invalid payload");
    //         return response('Invalid payload', 400);
    //     } catch (\Stripe\Exception\SignatureVerificationException $e) {
    //         Log::error("Stripe Webhook error: Invalid signature");
    //         return response('Invalid signature', 400);
    //     }

    //     // if ($event->type === 'checkout.session.completed') {
    //     //     $session = $event->data->object;

    //     //     $userId = $session->metadata->user_id ?? null;
    //     //     $amount = $session->amount_total / 100;
    //     //     $paymentIntentId = $session->payment_intent;

    //     //     if ($userId && $paymentIntentId) {
    //     //         $user = User::find($userId);

    //     //         if ($user) {
    //     //             // ✅ Avoid duplicate transaction using payment_intent
    //     //             $exists = TransactionHistory::where('user_id', $user->id)
    //     //                 ->where('type', 'deposit')
    //     //                 ->where('trans_type', 'stripe')
    //     //                 ->where('reference', $paymentIntentId) // <-- store unique intent
    //     //                 ->exists();

    //     //             if (!$exists) {
    //     //                 // Update balance
    //     //                 $user->balance += $amount;
    //     //                 $user->save();

    //     //                 // Create transaction
    //     //                 TransactionHistory::create([
    //     //                     'user_id'        => $user->id,
    //     //                     'type'           => 'deposit',
    //     //                     'amount'         => $amount,
    //     //                     'status'         => 1,
    //     //                     'is_sent'        => 0,
    //     //                     'trans_type'     => 'stripe',
    //     //                     'payment_status' => 'approved',
    //     //                     'reference'      => $paymentIntentId, // <-- unique
    //     //                 ]);
    //     //             }
    //     //         }
    //     //     }
    //     // }

    //     if ($event->type === 'checkout.session.completed') {
    //         $session = $event->data->object;

    //         $userId = $session->metadata->user_id ?? null;
    //         $amount = $session->amount_total / 100;
    //         $paymentIntentId = $session->payment_intent;

    //         Log::info("Stripe Webhook Checkout Completed", [
    //             'user_id' => $userId,
    //             'amount'  => $amount,
    //             'payment_intent' => $paymentIntentId,
    //         ]);

    //         if ($userId && $paymentIntentId) {
    //             $user = User::find($userId);

    //             if ($user) {
    //                 Log::info("User found, updating balance", [
    //                     'before_balance' => $user->balance,
    //                     'add_amount' => $amount,
    //                 ]);

    //                 $exists = TransactionHistory::where('user_id', $user->id)
    //                     ->where('type', 'deposit')
    //                     ->where('trans_type', 'stripe')
    //                     ->where('reference', $paymentIntentId)
    //                     ->exists();

    //                 if (!$exists) {
    //                     $user->balance += $amount;
    //                     $user->save();

    //                     TransactionHistory::create([
    //                         'user_id'        => $user->id,
    //                         'type'           => 'deposit',
    //                         'amount'         => $amount,
    //                         'status'         => 1,
    //                         'is_sent'        => 0,
    //                         'trans_type'     => 'stripe',
    //                         'payment_status' => 'approved',
    //                         'reference'      => $paymentIntentId,
    //                     ]);

    //                     Log::info("Stripe deposit recorded", [
    //                         'user_id' => $user->id,
    //                         'new_balance' => $user->balance,
    //                     ]);
    //                 } else {
    //                     Log::warning("Stripe deposit already exists", [
    //                         'user_id' => $user->id,
    //                         'reference' => $paymentIntentId,
    //                     ]);
    //                 }
    //             } else {
    //                 Log::error("User not found for Stripe deposit", ['user_id' => $userId]);
    //             }
    //         }
    //     }

    //     return ApiHelper::sendResponse(true, "Webhook handled", '', 200);
    // }




    public function handle(Request $request)
    {
        $payload   = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret    = config('services.stripe.webhook_secret');

        // Log secret carefully (array required, not string)
        Log::info("Stripe Webhook Secret", ['secret' => $secret]);

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);

            // Log the full event (this has the `type`)
            Log::info("Stripe Webhook Event", [
                'id'       => $event->id,
                'type'     => $event->type,
                'livemode' => $event->livemode,
            ]);

            // Log raw incoming webhook for debugging
            Log::info("Stripe Webhook received", [
                'signature' => $sigHeader,
                'payload'   => $payload,
            ]);
        } catch (\UnexpectedValueException $e) {
            Log::error("Stripe Webhook error: Invalid payload");
            return response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error("Stripe Webhook error: Invalid signature");
            return response('Invalid signature', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            Log::info("🔥 Stripe checkout.session.completed triggered", [
                'session_id'     => $session->id,
                'status'         => $session->status,
                'payment_status' => $session->payment_status,
                'metadata'       => $session->metadata,
            ]);

            $userId = $session->metadata->user_id ?? null;
            $amountMeta = $session->metadata->amount ?? null;
            $amount = $amountMeta ? (float)$amountMeta : ($session->amount_total / 100);
            $paymentIntentId = $session->payment_intent;

            if ($session->payment_status === 'paid') {
                if ($userId && $paymentIntentId) {
                    $user = User::find($userId);

                    if ($user) {
                        $exists = TransactionHistory::where('user_id', $user->id)
                            ->where('type', 'deposit')
                            ->where('trans_type', 'stripe')
                            ->where('reference', $paymentIntentId)
                            ->exists();

                        if (!$exists) {
                            $user->balance += $amount;
                            $user->save();

                            TransactionHistory::create([
                                'user_id'        => $user->id,
                                'type'           => 'deposit',
                                'amount'         => $amount,
                                'status'         => 1,
                                'is_sent'        => 0,
                                'trans_type'     => 'stripe',
                                'payment_status' => 'approved',
                                'reference'      => $paymentIntentId,
                            ]);

                            Log::info("✅ Stripe deposit recorded", [
                                'user_id'     => $user->id,
                                'new_balance' => $user->balance,
                            ]);
                        }
                    }
                }
            }
        }
    }
}
