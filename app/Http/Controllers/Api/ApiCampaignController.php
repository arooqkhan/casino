<?php

namespace App\Http\Controllers\Api;

use App\Models\Campaign;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiCampaignController extends Controller
{
    public function index(Request $request)
    {
        try {
            $campaigns = Campaign::all(); // or paginate with Campaign::paginate(10)

            return ApiResponse(true, "Campaign list retrieved successfully", $campaigns, 200);

        } catch (\Exception $e) {
            return ApiResponse(false, "Something went wrong", $e->getMessage(), 500);
        }
    }
}
