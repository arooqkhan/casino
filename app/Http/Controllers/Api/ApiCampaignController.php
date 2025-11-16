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
    //         // Campaign ke sath subscribers + winner user load karna
    //         $campaigns = Campaign::with(['subscribers', 'winnerUser'])->get();

    //         return ApiHelper::sendResponse(
    //             true,
    //             "Campaign list retrieved successfully",
    //             $campaigns
    //         );
    //     } catch (\Exception $e) {
    //         return ApiHelper::sendResponse(
    //             false,
    //             "Something went wrong",
    //             $e->getMessage(),
    //             500
    //         );
    //     }
    // }


  public function index(Request $request)
{
    try {
        $search = $request->input('search');
        $status = $request->input('status'); 
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);

        $query = Campaign::with(['subscribers', 'winnerUser']);

        // 🔍 Search filter
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // 🎯 Status filter
        if (!empty($status)) {
            $query->where('status', $status);
        }

        // ⚙️ Sorting logic
        $query->orderByRaw("
            CASE 
                WHEN status = 'active' THEN 0
                WHEN status = 'upcoming' THEN 1
                ELSE 2
            END
        ")
        ->orderByRaw("
            CASE 
                WHEN status = 'active' THEN end_at
                WHEN status = 'upcoming' THEN start_at
                ELSE end_at
            END ASC
        ");

        // 📌 SPECIAL CASE:
        // If status = active → show ALL active records in one page (no pagination)
        if ($status === 'active') {
            $campaigns = $query->get(); // all results

            return ApiHelper::sendResponse(
                true,
                "Active campaign list",
                [
                    "data" => $campaigns,
                    "current_page" => 1,
                    "last_page" => 1,
                    "per_page" => $campaigns->count(),
                    "total" => $campaigns->count(),
                ]
            );
        }

        // 📄 Normal pagination for other statuses
        $campaigns = $query->paginate($perPage, ['*'], 'page', $page);

        return ApiHelper::sendResponse(
            true,
            "Campaign list retrieved successfully",
            [
                "data" => $campaigns->items(),
                "current_page" => $campaigns->currentPage(),
                "last_page" => $campaigns->lastPage(),
                "per_page" => $campaigns->perPage(),
                "total" => $campaigns->total(),
            ]
        );
    } catch (\Exception $e) {
        return ApiHelper::sendResponse(false, "Something went wrong", $e->getMessage(), 500);
    }
}

}
