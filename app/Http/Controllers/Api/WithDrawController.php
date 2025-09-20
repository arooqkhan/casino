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
use App\Models\Card;

class WithDrawController extends Controller
{



    public function requestWithdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'method' => 'required|string', // stripe, bank, etc.
        ]);

        try {
            $user = Auth::user();

            if (!$user) {
                return ApiHelper::sendResponse(false, "User not authenticated", null, 401);
            }

            // ✅ Check balance
            if ($user->balance < $request->amount) {
                return ApiHelper::sendResponse(false, "Insufficient balance", null, 400);
            }

            // ✅ Create withdraw request (pending)
            $transaction = TransactionHistory::create([
                'user_id'       => $user->id,
                'type'          => 'withdraw',
                'amount'        => $request->amount,
                'status'        => 0, // 0 = pending
                'is_sent'       => 0,
                'trans_type'    => $request->method,
                'payment_status' => 'pending',
            ]);

            // ⚠️ Balance will NOT reduce until approved by admin
            // Admin panel or cron should later approve/reject and adjust balance.

            return ApiHelper::sendResponse(true, "Withdraw request submitted successfully", [
                'transaction' => $transaction,
                'current_balance' => $user->balance
            ], 201);
        } catch (\Exception $e) {
            return ApiHelper::sendResponse(false, "Something went wrong", $e->getMessage(), 500);
        }
    }





    public function storeBankDetails(Request $request)
    {
        $request->validate([
            'card_holder_name' => 'required|string|max:255',
            'card_number'      => 'required|string|max:255',
            'expiry_date'      => 'required|string|max:10',
            'ccv_code'         => 'required|string|max:10',
            'first_name'       => 'required|string|max:255',
            'last_name'        => 'required|string|max:255',
            'email'            => 'required|email|max:255',
            'province'         => 'required|string|max:255',
            'postal_code'      => 'required|string|max:50',
            'city'             => 'required|string|max:255',
            'country'          => 'required|string|max:255',
        ]);

        $user = Auth::user(); // user who is logged in

        $card = Card::create([
            'user_id'          => $user->id,
            'card_holder_name' => $request->card_holder_name,
            'card_number'      => $request->card_number,
            'expiry_date'      => $request->expiry_date,
            'ccv_code'         => $request->ccv_code,
            'first_name'       => $request->first_name,
            'last_name'        => $request->last_name,
            'email'            => $request->email,
            'province'         => $request->province,
            'postal_code'      => $request->postal_code,
            'city'             => $request->city,
            'country'          => $request->country,
        ]);

        return ApiHelper::sendResponse(true, "Bank details saved successfully", $card, 201);
    }


    public function getBankDetails(Request $request)
    {
        try {
            // ✅ If you have multiple bank details, you can filter
            // For now, return the latest one from admin
            $user = Auth::user();

            $card = Card::where('user_id', $user->id)->first();

            if (!$card) {
                return ApiHelper::sendResponse(false, "No bank details available", null, 404);
            }

            // ⚠️ Hide sensitive fields (like full card number, CVV)
            $safeCard = [
                'bank_name'       => $card->card_holder_name,
                'account_number'  => substr($card->card_number, -4), // last 4 digits only
                'expiry_date'     => $card->expiry_date,
                'first_name'      => $card->first_name,
                'last_name'       => $card->last_name,
                'email'           => $card->email,
                'province'        => $card->province,
                'postal_code'     => $card->postal_code,
                'city'            => $card->city,
                'country'         => $card->country,
            ];

            return ApiHelper::sendResponse(true, "Bank details fetched successfully", $safeCard, 200);
        } catch (\Exception $e) {
            return ApiHelper::sendResponse(false, "Something went wrong", $e->getMessage(), 500);
        }
    }
}
