<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use App\Models\UserDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\TransactionHistory;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class DepositController extends Controller
{


    public function depositCheckout(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        try {
            $user = Auth::user();
            if (!$user) {
                return ApiHelper::sendResponse(false, "User not authenticated", null, 401);
            }

            Stripe::setApiKey(config('services.stripe.secret'));

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency'     => 'usd',
                        'product_data' => [
                            'name' => 'Wallet Deposit',
                        ],
                        'unit_amount'  => $request->amount * 100, // cents
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('stripe.deposit.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'  => url('/api/stripe/deposit/cancel'),
                'metadata' => [
                    'user_id' => $user->id,
                    'amount'  => $request->amount,
                ],
            ]);

            return ApiHelper::sendResponse(true, "Stripe checkout created", [
                'checkout_url' => $session->url,
            ], 200);
        } catch (\Exception $e) {
            return ApiHelper::sendResponse(false, "Stripe error", $e->getMessage(), 500);
        }
    }

    // public function depositSuccess(Request $request)
    // {

    //     $sessionId = $request->get('session_id');

    //     if (!$sessionId) {
    //         return ApiHelper::sendResponse(false, "Missing session_id", null, 400);
    //     }

    //     \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
    //     $session = \Stripe\Checkout\Session::retrieve($sessionId);

    //     // 🔥 Log session data for debugging
    //     Log::info('Stripe Deposit Success Callback', [
    //         'session' => $session,
    //     ]);
    //     // ⚡ IMPORTANT: do NOT update balance here!
    //     // Webhook already does it.
    //    return redirect()->away('http://localhost:5173/my-account')
    // ->with('success', 'Payment successful! Your balance will be updated shortly.');
    //     // return ApiHelper::sendResponse(true, "Payment successful, balance will be updated shortly", null, 200);
    // }


    public function depositSuccess(Request $request)
    {
        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            return ApiHelper::sendResponse(false, "Missing session_id", null, 400);
        }



        Stripe::setApiKey(config('services.stripe.secret'));
        $session = Session::retrieve($sessionId);

        try {
            Log::info('✅ ssss Success ', [
                'session_id' => $session->id,
                'status'     => $session->status,
                'payment_status' => $session->payment_status,
            ]);

            // Do NOT update balance here → your webhook already does that
            return redirect()->away('https://megaspinn.vercel.app/my-account')
                ->with('success', 'Payment successful! Your balance will be updated shortly.');
        } catch (\Exception $e) {
            Log::error("❌ Failed to retrieve Stripe session", ['error' => $e->getMessage()]);
            return ApiHelper::sendResponse(false, "Could not verify payment", null, 500);
        }
    }


    public function depositCancel()
    {
        return ApiHelper::sendResponse(false, "Deposit canceled by user", null, 400);
    }


    public function index(Request $request)
    {
        try {
            // Campaign ke sath creator aur subscribers load karna
            $deposit = TransactionHistory::get();

            return ApiHelper::sendResponse(true, "Deposit and Withdraw retrieved successfully", $deposit);
        } catch (\Exception $e) {
            return ApiHelper::sendResponse(false, "Something went wrong", $e->getMessage(), 500);
        }
    }
}
