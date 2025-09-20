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
}
