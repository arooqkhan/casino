<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Bonus;
use App\Models\BonusUser;
use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiBonusController extends Controller
{
   public function index(Request $request)
{
    try {
        // agar tumhe pagination chahiye to:
        // $bonuses = Bonus::paginate(10);
        $bonuses = Bonus::all();

        return ApiHelper::sendResponse(true, "Bonus list retrieved successfully", $bonuses, 200);

    } catch (\Exception $e) {
        return ApiHelper::sendResponse(false, "Something went wrong", $e->getMessage(), 500);
    }
}



  public function purchase(Request $request)
    {
        $request->validate([
            'bonus_id' => 'required|exists:bonuses,id',
            'credit'   => 'required|integer|min:1',
        ]);

        $user = auth()->user();

        // Check balance
        if ($user->total_credit < $request->credit) {
            return response()->json([
                'status'  => false,
                'message' => 'Insufficient balance',
            ], 400);
        }

        // Deduct credit from user
        $user->total_credit -= $request->credit;
        $user->save();

        // Create entry in bonus_users
        BonusUser::create([
            'bonus_id' => $request->bonus_id,
            'user_id'  => $user->id,
            'time'     => Carbon::now(),
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Bonus purchased successfully',
            'data'    => [
                'user_id'    => $user->id,
                'bonus_id'   => $request->bonus_id,
                'remaining'  => $user->total_credit,
            ]
        ]);
    }

    

}
