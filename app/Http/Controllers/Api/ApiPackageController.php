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
        $packages = Package::all()->map(function($package) {
            if ($package->icon) {
                // Convert spaces to %20 and get full URL
                $package->icon = str_replace(' ', '%20', asset($package->icon));
            }
            return $package;
        });

        return ApiHelper::sendResponse(true, "Packages list retrieved successfully", $packages);

    } catch (\Exception $e) {
        return ApiHelper::sendResponse(false, "Something went wrong", $e->getMessage(), 500);
    }
}


}
