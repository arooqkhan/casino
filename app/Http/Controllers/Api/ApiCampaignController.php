<?php

namespace App\Http\Controllers\Api;

use App\Models\Campaign;
use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiCampaignController extends Controller
{

    // public function index(Request $request)
    // {
    //     try {
    //         // Campaign ke sath creator aur subscribers load karna
    //         $campaigns = Campaign::with(['subscribers'])->get();

    //         return ApiHelper::sendResponse(true, "Campaign list retrieved successfully", $campaigns);

    //     } catch (\Exception $e) {
    //         return ApiHelper::sendResponse(false, "Something went wrong", $e->getMessage(), 500);
    //     }
    // }

    public function index(Request $request)
    {
        try {
            // Campaign ke sath subscribers + winner user load karna
            $campaigns = Campaign::with(['subscribers', 'winnerUser'])->get();

            return ApiHelper::sendResponse(
                true,
                "Campaign list retrieved successfully",
                $campaigns
            );
        } catch (\Exception $e) {
            return ApiHelper::sendResponse(
                false,
                "Something went wrong",
                $e->getMessage(),
                500
            );
        }
    }
}
