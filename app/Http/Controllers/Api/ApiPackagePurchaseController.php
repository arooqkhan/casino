<?php

namespace App\Http\Controllers\Api;

use Stripe\Stripe;
use App\Models\User;
use App\Models\Package;
use Stripe\StripeClient;
use App\Helpers\ApiHelper;
use App\Models\PackageUser;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use App\Models\CampaignSubscribe;
use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ApiPackagePurchaseController extends Controller
{
    public function createCheckout(Request $request)
    {
        $validated = $request->validate([
            'package_id' => 'required|exists:packages,id',

        ]);
        $user = Auth::user();
        $balance = $user->balance;
        $package = Package::findOrFail($validated['package_id']);
        if ($balance < $package->price) {
            return ApiHelper::sendResponse(false, "Insufficient balance", '', 400);
        }
        $user->balance -= $package->price;
        $user->total_credit += $package->credit;
        $user->save();
        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency'     => 'usd',
                        'product_data' => [
                            'name' => $package->name,
                        ],
                        'unit_amount'  => $package->price * 100, // cents
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => 'https://megaspinn.vercel.app/my-account', // ✅ frontend
                'cancel_url' => url('/api/stripe/cancel'),
            ]);


            Log::info('Stripe sessionm what is  ....', [
                'session' => $session->url,
            ]);


            return response()->json([
                'success'      => true,
                'checkout_url' => $session->url,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Stripe error: ' . $e->getMessage(),
            ], 500);
        }
    }



    public function success(Request $request)
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $session = \Stripe\Checkout\Session::retrieve($request->session_id);

            if ($session->payment_status === 'paid') {
                $user    = User::findOrFail($request->user_id);
                $package = Package::findOrFail($request->package_id);

                // ✅ Add credits only after success
                $user->total_credit += $package->credit;
                $user->save();

                return redirect()->away('https://megaspinn.vercel.app/my-account')
                    ->with('success', 'Payment successful! Your balance will be updated shortly.');
            }

            return redirect(config('services.frontend.url') . '/payment-failed');
        } catch (\Exception $e) {
            return redirect(config('services.frontend.url') . '/payment-error?message=' . urlencode($e->getMessage()));
        }
    }

    public function cancel()
    {
        return redirect(config('services.frontend.url') . '/dashboard?status=cancel');
    }








    public function joinCampaign(Request $request)
    {
        $user = Auth::user();
        $campaignId = $request->input('campaign_id');

        if (!$campaignId) {
            return ApiHelper::sendResponse(false, "Campaign ID is required", '', 422);
        }

        $campaign = Campaign::find($campaignId);

        if (!$campaign) {
            return ApiHelper::sendResponse(false, "Campaign not found", '', 404);
        }

        // ✅ Check if user has enough credits for campaign
        if ($user->total_credit < $campaign->credit) {
            return ApiHelper::sendResponse(false, "Insufficient credits", '', 400);
        }

        try {
            // ✅ Deduct credits
            $user->total_credit -= $campaign->credit;
            $user->save();

            // ✅ Save in campaign_subscribe
            $subscribe = CampaignSubscribe::create([
                'user_id'     => $user->id,
                'campaign_id' => $campaignId,
            ]);

            return ApiHelper::sendResponse(true, "Successfully joined campaign", [
                'subscription'     => $subscribe,
                'remaining_credit' => $user->total_credit,
            ], 200);
        } catch (\Exception $e) {
            return ApiHelper::sendResponse(false, "Failed to join campaign", $e->getMessage(), 500);
        }
    }


    public function getAllCompaign()
    {
        // Get all users with their subscribed campaigns
        $users = User::with(['campaigns' => function ($query) {
            $query->select('campaigns.id', 'campaigns.name', 'campaigns.status');
        }])->get(['id', 'first_name', 'last_name', 'email']);

        return ApiHelper::sendResponse(true, "Campaign list", $users);
    }
}
