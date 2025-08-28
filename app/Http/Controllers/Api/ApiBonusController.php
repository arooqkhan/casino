<?php

namespace App\Http\Controllers\Api;

use App\Models\Bonus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiBonusController extends Controller
{
    public function index(Request $request)
    {
        try {
            $bonuses = Bonus::all(); // or use pagination: Bonus::paginate(10);

            return ApiResponse(true, "Bonus list retrieved successfully", $bonuses, 200);

        } catch (\Exception $e) {
            return ApiResponse(false, "Something went wrong", $e->getMessage(), 500);
        }
    }
}
