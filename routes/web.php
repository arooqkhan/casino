<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController\UserController;
use App\Http\Controllers\AdminController\BonusController;
use App\Http\Controllers\AdminController\WalletController;
use App\Http\Controllers\AdminController\CreatedController;
use App\Http\Controllers\AdminController\PackageController;
use App\Http\Controllers\AdminController\CampaignController;
use App\Http\Controllers\AdminController\DashboardController;
use App\Http\Controllers\AdminController\UserProfileController;
use App\Http\Controllers\AdminController\TransactionHistoryController;

Route::middleware('auth')->group(function () {
    
    Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');



    Route::resource('users', UserController::class);

    Route::resource('transaction_histories', TransactionHistoryController::class);

    Route::resource('bonus', BonusController::class);

    Route::resource('campaigns', CampaignController::class);


    Route::resource('createds', CreatedController::class);

    Route::resource('packages', PackageController::class);


    Route::resource('wallet', WalletController::class);



    Route::prefix('wallet')->name('wallet.')->group(function () {
        Route::post('/add-money', [WalletController::class, 'addMoney'])->name('add.money');
        Route::post('/withdraw-money', [WalletController::class, 'withdrawMoney'])->name('withdraw.money');
    });


   Route::get('userprofile', [UserProfileController::class, 'edit'])->name('userprofile');
   Route::put('userprofile', [UserProfileController::class, 'update'])->name('userprofile.update');


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==============================strip payment===========================


Route::prefix('admin')->middleware(['auth',])->group(function () {
    Route::post('/transactions/{id}/approve', [TransactionHistoryController::class, 'approve'])
        ->name('admin.transactions.approve');

    Route::post('/transactions/{id}/reject', [TransactionHistoryController::class, 'reject'])
        ->name('admin.transactions.reject');
    // Route::delete('/transactions/{id}', [TransactionHistoryController::class, 'destroy'])->name('admin.transactions.destroy');
});



// Route::get('/payment', [PaymentController::class, 'index'])->name('payment.form');
// Route::post('/checkout', [PaymentController::class, 'checkout'])->name('payment.checkout');
// Route::get('/success', function () {
//     return "Payment Successful!";
// })->name('payment.success');
// Route::get('/cancel', function () {
//     return "Payment Canceled!";
// })->name('payment.cancel');


// Route::get('/payment', [PaymentController::class, 'index'])->name('payment.index');
Route::post('/payment/checkout', [PaymentController::class, 'checkout'])->name('payment.checkout');
Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');

// Webhook (must be POST, set this URL in Stripe dashboard)
Route::post('/stripe/webhook', [PaymentController::class, 'webhook']);

require __DIR__ . '/auth.php';
