<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController\UserController;
use App\Http\Controllers\AdminController\BonusController;
use App\Http\Controllers\AdminController\CreatedController;
use App\Http\Controllers\AdminController\CampaignController;
use App\Http\Controllers\AdminController\TransactionHistoryController;

// Route::get('/', function () {
//     return view('dashboard');
// });

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {


    Route::resource('users', UserController::class);

    Route::resource('transaction_histories', TransactionHistoryController::class);

     Route::resource('bonus', BonusController::class);

     Route::resource('campaigns', CampaignController::class);


     Route::resource('createds', CreatedController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
