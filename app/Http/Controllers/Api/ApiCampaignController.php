<?php

namespace App\Http\Controllers\Api;

use App\Models\Campaign;
use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiCampaignController extends Controller
{
   public function index(Request $request)
{
    try {
        
        // $campaigns = Campaign::paginate(10);
        $campaigns = Campaign::all();

        return ApiHelper::sendResponse(true, "Campaign list retrieved successfully", $campaigns, 200);

    } catch (\Exception $e) {
        return ApiHelper::sendResponse(false, "Something went wrong", $e->getMessage(), 500);
    }
}
}
