<?php

use App\Models\Bonus;
use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiFaqController;
use App\Http\Controllers\Api\ApiBonusController;
use App\Http\Controllers\Api\ApiPackageController;
use App\Http\Controllers\Api\ApiCampaignController;
use Chatify\Http\Controllers\Api\MessagesController;
use App\Http\Controllers\Api\ApiPackagePurchaseController;
use App\Http\Controllers\Api\ApiWinningCompaignController;
use App\Http\Controllers\AdminController\ContactUsController;
use App\Http\Controllers\Api\DepositController;
use App\Http\Controllers\Api\ProfileController; // make sure this exists
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Api\UserDocumentController;
use App\Models\TransactionHistory;

use App\Http\Controllers\Api\StripeWebhookController;
use App\Http\Controllers\Api\WithDrawController;
use App\Models\Campaign;

Route::prefix('v1')->group(function () {

  // Public route
  Route::post('/login', [ProfileController::class, 'login']);

  Route::post('/register', [ProfileController::class, 'register']);

  // Email verification routes
  Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return response()->json(['message' => 'Email verified successfully.']);
  })->middleware(['auth:sanctum', 'signed'])->name('verification.verify');

  Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return response()->json(['message' => 'Verification link sent!']);
  })->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send');



  Route::post('/forgot-password', [ProfileController::class, 'forgotPassword']);
  Route::post('/verify-otp', [ProfileController::class, 'verifyOtp']);
  Route::post('/reset-password', [ProfileController::class, 'resetPassword']);

  // Authenticated routes
  Route::middleware('auth:sanctum')->group(function () {


    Route::get('/profile', [ProfileController::class, 'profile']);


    Route::post('/profile-update', [ProfileController::class, 'updateProfile']);

    Route::post('/bank-details', [WithDrawController::class, 'storeBankDetails']);
    Route::get('/bank-details', [WithDrawController::class, 'getBankDetails']);




    Route::get('/user/{id}', [ProfileController::class, 'show']);

    Route::get('/bonuses', [ApiBonusController::class, 'index']);

    Route::post('/claim-bonus', [ApiBonusController::class, 'store']);

    Route::get('/campaigns', [ApiCampaignController::class, 'index']);

    Route::get('/packages', [ApiPackageController::class, 'index']);



    Route::post('/purchase-package', [ApiPackagePurchaseController::class, 'createCheckout']);

    Route::post('/joinCampaign', [ApiPackagePurchaseController::class, 'joinCampaign']);

    Route::get('/getAllCampaign', [ApiPackagePurchaseController::class, 'getAllCompaign']);


    Route::post('/campaigns/declare-winner', [ApiWinningCompaignController::class, 'declareWinner']);


    Route::post('/purchase-bonus', [ApiBonusController::class, 'purchase']);

    /////////////////////// strip work by tech===================

    Route::post('/withdraw/request', [WithDrawController::class, 'requestWithdraw']);
    Route::post('/deposit/checkout', [DepositController::class, 'depositCheckout']);
    Route::get('/deposit/cancel', [DepositController::class, 'depositCancel']);


    Route::post('/contact-us', [ContactUsController::class, 'store']);


    Route::get('/faqs', [ApiFaqController::class, 'listApi']);



    Route::get('/deposit', [DepositController::class, 'index']);



    // Chatify apis

    Route::post('/sendMessage', [MessagesController::class, 'send'])->name('api.send.message');

    Route::post('/fetchMessages', [MessagesController::class, 'fetch'])->name('api.fetch.messages');

    Route::get('/getContacts', [MessagesController::class, 'getContacts'])->name('api.contacts.get');

    Route::post('/chat/auth', [MessagesController::class, 'pusherAuth'])->name('api.pusher.auth');

    Route::post('/makeSeen', [MessagesController::class, 'seen'])->name('api.messages.seen');





    Route::post('/kyc/upload', [UserDocumentController::class, 'store']);
    Route::post('/requestWithdraw', [UserDocumentController::class, 'requestWithdraw']);
  });
});
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']); //webhook
Route::get('/deposit/success', [DepositController::class, 'depositSuccess'])->name('stripe.deposit.success');




/////////====================== cronejob ====================================


Route::get('/campaigns/{id}', function ($id) {
  $campaign = Campaign::with('winnerUser')->find($id);
  if (!$campaign) {
    return ApiHelper::sendResponse(
      false,
      "Campaign not found",
      null,
      404
    );
  }
  return ApiHelper::sendResponse(
    true,
    "Campaign retrieved successfully",
    [
      'campaign_id' => $campaign->id,
      'status'      => $campaign->status,
      'winner'      => $campaign->winnerUser ? [
        'id'    => $campaign->winnerUser->id,
        'name'  => $campaign->winnerUser->first_name . ' ' . $campaign->winnerUser->last_name,
        'prize' => $campaign->winner_price, // make sure this column exists
      ] : null,
    ],
    200
  );
});
// for frontend
// import { useEffect, useState } from "react";

// export default function CampaignChecker({ campaignId }) {
//   const [campaign, setCampaign] = useState(null);
//   const [winner, setWinner] = useState(null);

//   useEffect(() => {
//     let intervalId;

//     async function checkCampaign() {
//       try {
//         const res = await fetch(`/api/campaigns/${campaignId}`);
//         const json = await res.json();
//         const data = json.data;

//         if (!data) return;

//         setCampaign(data);

//         if (data.status === "expired" && data.winner) {
//           setWinner(data.winner);

//           // ✅ Animate spin wheel (replace with real animation)
//           spinWheelToWinner(data.winner.id);

//           // Stop polling once we have a winner
//           clearInterval(intervalId);
//         }
//       } catch (err) {
//         console.error("Error fetching campaign:", err);
//       }
//     }

//     // Run once immediately
//     checkCampaign();

//     // Then poll every 30s
//     intervalId = setInterval(checkCampaign, 30000);

//     // Cleanup interval when component unmounts
//     return () => clearInterval(intervalId);
//   }, [campaignId]);

//   // Dummy spin wheel function (replace with actual animation logic)
//   function spinWheelToWinner(userId) {
//     alert(`🎉 Winner is user #${userId}!`);
//   }

//   return (
//     <div className="p-4 border rounded">
//       <h2 className="text-xl font-bold">Campaign #{campaignId}</h2>

//       {campaign && (
//         <>
//           <p>Status: {campaign.status}</p>
//           {winner && (
//             <p className="text-green-600">
//               Winner: {winner.name} 🎁 ({winner.prize})
//             </p>
//           )}
//         </>
//       )}

//       {!campaign && <p>Loading campaign...</p>}
//     </div>
//   );
// }
