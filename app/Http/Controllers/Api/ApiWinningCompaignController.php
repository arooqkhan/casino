<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Campaign;
use App\Helpers\ApiHelper;
use App\Helpers\MailHelper;
use Illuminate\Http\Request;
use App\Models\TransactionHistory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ApiWinningCompaignController extends Controller
{
    //    public function declareWinner(Request $request)
    // {
    //     $request->validate([
    //         'user_id' => 'required|exists:users,id',
    //         'campaign_id' => 'required|exists:campaigns,id',
    //     ]);

    //     try {
    //         // 1. Get Campaign
    //         $campaign = Campaign::findOrFail($request->campaign_id);

    //         // Make sure campaign has winning_price
    //         if (!$campaign->winner_price) {
    //             return ApiHelper::sendResponse(false, "This campaign has no winning price set", null, 400);
    //         }

    //         $winningPrice = $campaign->winner_price;

    //         // 2. Create Transaction History
    //         $transaction = new TransactionHistory();
    //         $transaction->user_id = $request->user_id;
    //         $transaction->type = 'deposit';
    //         $transaction->amount = $winningPrice;
    //         $transaction->status = 1; // ✅ 1 = success/approved
    //         $transaction->is_sent = 0;
    //         $transaction->trans_type = 'winning price';
    //         $transaction->payment_status = 'approved';
    //         $transaction->save();

    //         // 3. Update User Balance
    //         $user = User::findOrFail($request->user_id);
    //         $user->balance += $winningPrice;
    //         $user->save();

    //         return ApiHelper::sendResponse(true, "Winner declared successfully", [
    //             'transaction' => $transaction,
    //             'user' => $user,
    //         ], 200);

    //     } catch (\Exception $e) {
    //         return ApiHelper::sendResponse(false, "Something went wrong", $e->getMessage(), 500);
    //     }
    // }


    public function declareWinner(Request $request)
    {
        
        $request->validate([
            'campaign_id' => 'required|exists:campaigns,id',
            'user_id' => 'nullable|exists:users,id', // nullable for draw
        ]);

        try {
            // 1. Get Campaign
            $campaign = Campaign::findOrFail($request->campaign_id);

            // Check if already expired (avoid duplicate declarations)
            if ($campaign->status === 'expired') {
                return ApiHelper::sendResponse(false, "This campaign is already expired", null, 400);
            }

            // 🎯 Case 1: DRAW (no winner)
            if (is_null($request->user_id)) {
                DB::table('campaign_subscribe')
                    ->where('campaign_id', $campaign->id)
                    ->update(['result' => 'draw']);

                $campaign->status = 'expired';
                $campaign->end_at = now();
                $campaign->save();

                return ApiHelper::sendResponse(true, "Campaign ended in a draw", [
                    'campaign_id'     => $campaign->id,
                    'campaign_status' => $campaign->status,
                ], 200);
            }

            // 🎯 Case 2: WINNER declared
            if (!$campaign->winner_price) {
                return ApiHelper::sendResponse(false, "This campaign has no winning price set", null, 400);
            }

            $winningPrice = $campaign->winner_price;

            // 2. Mark all users as loss
            DB::table('campaign_subscribe')
                ->where('campaign_id', $campaign->id)
                ->update(['result' => 'loss']);

            // 3. Mark the given user as winner
            DB::table('campaign_subscribe')
                ->where('campaign_id', $campaign->id)
                ->where('user_id', $request->user_id)
                ->update(['result' => 'win']);

            // 4. Create Transaction History for winner
            $transaction = new TransactionHistory();
            $transaction->user_id = $request->user_id;
            $transaction->type = 'deposit';
            $transaction->amount = $winningPrice;
            $transaction->status = 1; // approved
            $transaction->is_sent = 0;
            $transaction->trans_type = 'winning prize';
            $transaction->payment_status = 'approved';
            $transaction->save();

            // 5. Update User Balance
            $user = User::findOrFail($request->user_id);
            $user->balance += $winningPrice;
            $user->save();


            // 6 Send Winner Email
            $subject = "🎉 Congratulations! You Won the Campaign Prize";
            $message =  "Dear {$user->first_name} {$user->last_name},\n\n" .
                "Congratulations! You have been selected as the winner for the campaign '{$campaign->title}'.\n" .
                "You have received a prize of {$winningPrice} credits which has been added to your account.\n\n" .
                "Best regards,\nThe Campaign Team";

            MailHelper::sendMail($user->email, $subject, $message);

            // 7. Expire the campaign
            $campaign->status = 'expired';
            $campaign->end_at = now();
            $campaign->save();

            return ApiHelper::sendResponse(true, "Winner declared and campaign expired successfully", [
                'campaign_id'     => $campaign->id,
                'winner_user_id'  => $user->id,
                'transaction'     => $transaction,
                'user'            => $user,
                'campaign_status' => $campaign->status,
            ], 200);
        } catch (\Exception $e) {
            return ApiHelper::sendResponse(false, "Something went wrong", $e->getMessage(), 500);
        }
    }
}
