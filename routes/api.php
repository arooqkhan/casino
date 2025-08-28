<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController; // make sure this exists

Route::prefix('v1')->group(function () {

  // Public route
  Route::post('/login', [AuthController::class, 'login']);

  // Authenticated routes
  Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
      return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
  });

  // Test ping route
  Route::get('/ping', function () {
    return response()->json(['message' => 'pong']);
  });
});
