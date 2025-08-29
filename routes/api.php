<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiBonusController;
use App\Http\Controllers\Api\ApiCampaignController;
use App\Http\Controllers\Api\ProfileController; // make sure this exists

Route::prefix('v1')->group(function () {

  // Public route
  Route::post('/login', [ProfileController::class, 'login']);

  Route::post('/register', [ProfileController::class, 'register']);

  // Authenticated routes
  Route::middleware('auth:sanctum')->group(function () {


     Route::post('/list_profile', [ProfileController::class, 'index']);

      Route::get('/user/{id}', [ProfileController::class, 'show']);

      Route::get('/bonuses', [ApiBonusController::class, 'index']);

      Route::get('/campaigns', [ApiCampaignController::class, 'index']);

    
  });




});
