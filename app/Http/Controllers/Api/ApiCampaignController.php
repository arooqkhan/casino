<?php

namespace App\Http\Controllers\Api;

use App\Models\Campaign;
use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ApiCampaignController extends Controller
{

public function index(Request $request)
{
    try {
        // ✅ Campaigns with subscribers
        $campaigns = Campaign::with(['subscribers'])->get();

        // ✅ Transaction history (claim bonus records)
        $claimBonus = DB::table('transaction_histories')
         
          
            ->orderBy('created_at', 'desc')
            ->get();

        // ✅ Response
        $data = [
            'campaigns'    => $campaigns,
            'claim_bonus'  => $claimBonus,
        ];

        return ApiHelper::sendResponse(true, "Campaign list retrieved successfully", $data);

    } catch (\Exception $e) {
        return ApiHelper::sendResponse(false, "Something went wrong", $e->getMessage(), 500);
    }
}








}
