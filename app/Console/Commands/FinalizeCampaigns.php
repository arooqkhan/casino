<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Campaign;
use App\Models\CampaignSubscribe;
use App\Models\User;
use Illuminate\Support\Facades\Log;

use App\Helpers\MailHelper;
use Illuminate\Http\Request;
use App\Models\TransactionHistory;
use Illuminate\Support\Facades\DB;

class FinalizeCampaigns extends Command
{
    protected $signature = 'campaigns:finalize';
    protected $description = 'Finalize campaigns whose end_at has passed and pick a winner or mark as draw';

    public function handle()
    {
        $campaigns = Campaign::where('status', 'active')
            ->where('end_at', '<=', now())
            ->get();

        Log::info("FinalizeCampaigns started", [
            'campaign_count' => $campaigns->count(),
            'time' => now()->toDateTimeString(),
        ]);

        foreach ($campaigns as $campaign) {
            Log::info("Processing campaign", $campaign->toArray());

            $winner = CampaignSubscribe::where('campaign_id', $campaign->id)
                ->inRandomOrder()
                ->first();

            if ($winner) {
                // winner exists
                $winner->update([
                    'result' => 'win',
                    'updated_at' => now(),
                ]);

                CampaignSubscribe::where('campaign_id', $campaign->id)
                    ->where('id', '!=', $winner->id)
                    ->update([
                        'result' => 'loss',
                        'updated_at' => now(),
                    ]);




                $campaign->update([
                    'status'     => 'expired',
                    'updated_at' => now(),
                ]);


                // ✅ Add winner prize to user's balance
                $user = User::find($winner->user_id);
                if ($user && $campaign->winner_price > 0) {
                    $user->balance += $campaign->winner_price;
                    $user->save();

                    Log::info("Winner balance updated", [
                        'user_id' => $user->id,
                        'added_amount' => $campaign->winner_price,
                        'new_balance' => $user->balance,
                    ]);


                    // ✅ 6. Send Winner Email
                    try {
                        $subject = "🎉 Congratulations! You Won the Campaign Prize";
                        $message =  "Dear {$user->first_name} {$user->last_name},\n\n" .
                            "Congratulations! You have been selected as the winner for the campaign '{$campaign->name}'.\n" .
                            "You have received a prize of {$campaign->winner_price} credits which has been added to your account.\n\n" .
                            "Best regards,\nThe Campaign Team";

                        MailHelper::sendMail($user->email, $subject, $message);

                        Log::info("Winner email sent", [
                            'user_email' => $user->email,
                            'campaign_id' => $campaign->id
                        ]);
                    } catch (\Exception $e) {
                        Log::error("Failed to send winner email", [
                            'error' => $e->getMessage(),
                            'user_email' => $user->email ?? 'unknown',
                        ]);
                    }
                }

                Log::info("Winner chosen", ['campaign_id' => $campaign->id, 'winner_id' => $winner->user_id]);
            } else {
                // no participants → mark as expired_no_winner
                $campaign->update([
                    'status'     => 'expired_no_winner',
                    'updated_at' => now(),
                ]);

                Log::warning("No participants, marked as draw", ['campaign_id' => $campaign->id]);
            }



            $this->info("Campaign {$campaign->id} finalized.");
        }

        Log::info("FinalizeCampaigns completed at " . now());

        return Command::SUCCESS;
    }
}
