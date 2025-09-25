<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Campaign;
use App\Models\CampaignSubscribe;
use Illuminate\Support\Facades\Log;

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
