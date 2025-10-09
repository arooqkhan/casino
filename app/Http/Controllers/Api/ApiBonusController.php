<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Bonus;
use App\Models\BonusUser;
use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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



    public function claimBonus(Request $request)
    {

        try {
            $validated = $request->validate([
                'bonus_id' => 'required|exists:bonuses,id',
            ]);

            $user = Auth::user();

            if (!$user) {
                return ApiHelper::sendResponse(false, "User not authenticated", null, 401);
            }

            // Check agar already claim kar chuka hai
            $alreadyClaimed = DB::table('bonus_users')
                ->where('user_id', $user->id)
                ->where('bonus_id', $validated['bonus_id'])
                ->first();

            if ($alreadyClaimed) {
                return ApiHelper::sendResponse(false, "Bonus already claimed.", null, 409);
            }

            // Get the bonus details
            $bonus = DB::table('bonuses')->where('id', $validated['bonus_id'])->first();

            if (!$bonus) {
                return ApiHelper::sendResponse(false, "Bonus not found.", null, 404);
            }

            // Add bonus credit to user total credit
            $user->total_credit += $bonus->credit;
            $user->save();

            // Insert bonus claim record
            $bonusClaim = DB::table('bonus_users')->insertGetId([
                'bonus_id' => $validated['bonus_id'],
                'user_id'  => $user->id,
                'time'     => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return ApiHelper::sendResponse(
                true,
                "Bonus claimed successfully!",
                [
                    'bonus_user_id' => $bonusClaim,
                    'bonus_id'      => $validated['bonus_id'],
                    'user_id'       => $user->id,
                    'claimed_at'    => now(),
                ],

            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return ApiHelper::sendResponse(
                false,
                "Validation failed.",
                $e->errors(),

            );
        } catch (\Exception $e) {
            return ApiHelper::sendResponse(
                false,
                "Something went wrong.",
                $e->getMessage(),

            );
        }
    }
}
