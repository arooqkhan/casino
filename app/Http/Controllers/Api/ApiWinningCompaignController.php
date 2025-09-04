<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Campaign;
use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use App\Models\TransactionHistory;
use App\Http\Controllers\Controller;

class ApiWinningCompaignController extends Controller
{
   public function declareWinner(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'campaign_id' => 'required|exists:campaigns,id',
    ]);

    try {
        // 1. Get Campaign
        $campaign = Campaign::findOrFail($request->campaign_id);

        // Make sure campaign has winning_price
        if (!$campaign->winner_price) {
            return ApiHelper::sendResponse(false, "This campaign has no winning price set", null, 400);
        }

        $winningPrice = $campaign->winner_price;

        // 2. Create Transaction History
        $transaction = new TransactionHistory();
        $transaction->user_id = $request->user_id;
        $transaction->type = 'deposit';
        $transaction->amount = $winningPrice;
        $transaction->status = 1; // ✅ 1 = success/approved
        $transaction->is_sent = 0;
        $transaction->trans_type = 'winning price';
        $transaction->payment_status = 'approved';
        $transaction->save();

        // 3. Update User Balance
        $user = User::findOrFail($request->user_id);
        $user->balance += $winningPrice;
        $user->save();

        return ApiHelper::sendResponse(true, "Winner declared successfully", [
            'transaction' => $transaction,
            'user' => $user,
        ], 200);

    } catch (\Exception $e) {
        return ApiHelper::sendResponse(false, "Something went wrong", $e->getMessage(), 500);
    }
}
}
