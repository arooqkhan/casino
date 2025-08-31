<?php

namespace App\Http\Controllers\Api;

use App\Models\Package;
use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiPackageController extends Controller
{
      public function index(Request $request)
{
    try {
        // agar tumhe pagination chahiye to:
        // $bonuses = Bonus::paginate(10);
        $bonuses = Package::all();

        return ApiHelper::sendResponse(true, "Bonus list retrieved successfully", $bonuses, 200);

    } catch (\Exception $e) {
        return ApiHelper::sendResponse(false, "Something went wrong", $e->getMessage(), 500);
    }
}
}
