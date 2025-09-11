<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiBonusController;
use App\Http\Controllers\Api\ApiPackageController;
use App\Http\Controllers\Api\ApiCampaignController;
use App\Http\Controllers\Api\ApiPackagePurchaseController;
use App\Http\Controllers\Api\ApiWinningCompaignController;
use App\Http\Controllers\Api\ProfileController; // make sure this exists

  Route::prefix('v1')->group(function () {

  // Public route
  Route::post('/login', [ProfileController::class, 'login']);

  Route::post('/register', [ProfileController::class, 'register']);
  Route::post('/forgot-password', [ProfileController::class, 'forgotPassword']);
  Route::post('/verify-otp', [ProfileController::class, 'verifyOtp']);
  Route::post('/reset-password', [ProfileController::class, 'resetPassword']);

  // Authenticated routes
  Route::middleware('auth:sanctum')->group(function () {


    Route::get('/profile', [ProfileController::class, 'profile']);


    Route::post('/profile-update', [ProfileController::class, 'updateProfile']);






    Route::get('/user/{id}', [ProfileController::class, 'show']);

    Route::get('/bonuses', [ApiBonusController::class, 'index']);

    Route::get('/campaigns', [ApiCampaignController::class, 'index']);

    Route::get('/packages', [ApiPackageController::class, 'index']);



    Route::post('/purchase-package', [ApiPackagePurchaseController::class, 'createCheckout']);
    Route::post('/joinCampaign', [ApiPackagePurchaseController::class, 'joinCampaign']);

    Route::get('/getAllCampaign', [ApiPackagePurchaseController::class, 'getAllCompaign']);


    Route::post('/campaigns/declare-winner', [ApiWinningCompaignController::class, 'declareWinner']);
    
    Route::get('/stripe/cancel', function () {
      return response()->json(['success' => false, 'message' => 'Payment cancelled']);
    });
  });
});
