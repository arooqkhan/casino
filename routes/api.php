<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ApiBonusController;
use App\Http\Controllers\Api\ApiCampaignController;
use App\Http\Controllers\AdminController\CampaignController;




Route::prefix('v1')->group(function () {

 

    // Authenticated routes
    Route::middleware('auth:sanctum')->group(function () {

        // Profile routes
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');

        
        
      });
      
      Route::get('/bonus', [ApiBonusController::class, 'index'])->name('bonus.index');
      Route::get('/campaigns', [ApiCampaignController::class, 'index'])->name('campaigns.index');
      // Public routes (no auth required) can be added here
      
      Route::post('/register', [ProfileController::class, 'register'])->name('profile.register');
       Route::post('/login', [ProfileController::class, 'login'])->name('profile.login');

       
});

