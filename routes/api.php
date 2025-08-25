<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.v1.')->group(function () {

    // Authenticated routes
    Route::middleware('auth:sanctum')->group(function () {

        // Profile routes
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');

        // Add more authenticated routes here
        // e.g., Route::get('/bonus', [BonusController::class, 'index'])->name('bonus.index');
    });

    // Public routes (no auth required) can be added here
    // e.g., Route::post('/login', [AuthController::class, 'login'])->name('login');
});
