<?php


use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\AdminController\FaqController;
use App\Http\Controllers\AdminController\UserController;
use App\Http\Controllers\AdminController\BonusController;
use App\Http\Controllers\AdminController\WalletController;
use App\Http\Controllers\AdminController\CreatedController;
use App\Http\Controllers\AdminController\PackageController;
use App\Http\Controllers\AdminController\CampaignController;
use App\Http\Controllers\AdminController\ContactUsController;
use App\Http\Controllers\AdminController\DashboardController;
use App\Http\Controllers\AdminController\UserProfileController;
use App\Http\Controllers\AdminController\TransactionHistoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\TransactionHistory;

Route::middleware('auth')->group(function () {

    Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');



    Route::resource('users', UserController::class);

    Route::resource('transaction_histories', TransactionHistoryController::class);

    Route::resource('bonus', BonusController::class);

    Route::resource('campaigns', CampaignController::class);


    Route::resource('createds', CreatedController::class);

    Route::resource('packages', PackageController::class);


    Route::resource('wallet', WalletController::class);


    Route::resource('contactus', ContactUsController::class);

    Route::resource('faq', FaqController::class);



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



Route::get('/clear', function () {
    Artisan::call('optimize:clear');
    return "✅ All cache cleared successfully!";
});


Route::get('/migrate', function () {
    try {
        // Drop all tables and re-run all migrations
        // Artisan::call('migrate:fresh', [
        //     '--force' => true,
        // ]);

        Artisan::call('migrate', [
            '--force' => true,
        ]);

        // Run the DatabaseSeeder (which can call other seeders)
        // Artisan::call('db:seed', [
        //     '--force' => true,
        // ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Database migrated and seeded successfully.',
            'output' => Artisan::output(),
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
        ], 500);
    }
});


Route::get('/migrate-fresh', function () {

    // Drop all tables and re-run all migrations
    Artisan::call('migrate:fresh', [
        '--force' => true,
    ]);
});

Route::get('/seed', function () {
    try {
        Artisan::call('db:seed', [
            '--force' => true,
        ]);


        return response()->json([
            'status' => 'success',
            'message' => 'Database  seeded successfully.',
            'output' => Artisan::output(),
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
        ], 500);
    }
});




// Route::post('/stripe/webhook', function (Request $request) {
//     $payload = $request->getContent();
//     $sigHeader = $request->header('Stripe-Signature');
//     $secret = config('services.stripe.webhook_secret');

//     try {
//         $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $secret);
//     } catch (\UnexpectedValueException $e) {
//         return response('Invalid payload', 400);
//     } catch (\Stripe\Exception\SignatureVerificationException $e) {
//         return response('Invalid signature', 400);
//     }

//     if ($event->type === 'checkout.session.completed') {
//         $session = $event->data->object;

//         // ⚡ retrieve metadata
//         $userId = $session->metadata->user_id ?? null;
//         $amount = $session->amount_total / 100;

//         if ($userId) {
//             TransactionHistory::create([
//                 'user_id'        => $userId,
//                 'type'           => 'deposit',
//                 'amount'         => $amount,
//                 'status'         => 1,
//                 'is_sent'        => 0,
//                 'trans_type'     => 'stripe',
//                 'payment_status' => 'approved',
//             ]);
//         }
//     }

//     return response('Webhook handled', 200);
// });


Route::get('/test-finalize', function () {
    Artisan::call('campaigns:finalize');
    return response()->json([
        'status' => 'done',
        'output' => Artisan::output(),
    ]);
});

require __DIR__ . '/auth.php';
