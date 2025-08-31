<?php

namespace App\Http\Controllers\Api;

use Stripe\Stripe;
use App\Models\Package;
use Stripe\StripeClient;
use App\Models\PackageUser;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use App\Models\CampaignSubscribe;
use App\Http\Controllers\Controller;

class ApiPackagePurchaseController extends Controller
{
     public function createCheckout(Request $request)
    {
        $validated = $request->validate([
            'package_id' => 'required|exists:packages,id',
            'user_id'    => 'required|exists:users,id',
        ]);

        $package = Package::findOrFail($validated['package_id']);

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
                'success_url' => url('/api/stripe/success?session_id={CHECKOUT_SESSION_ID}&package_id=' . $package->id . '&user_id=' . $validated['user_id']),
                'cancel_url'  => url('/api/stripe/cancel'),
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
    $packageId = $request->query('package_id');
    $userId    = $request->query('user_id');
    $campaignId = $request->query('campaign_id'); // agar campaign bhi pass karna hai

    try {
        // ✅ Save in package_user
        PackageUser::create([
            'package_id' => $packageId,
            'user_id'    => $userId,
            'time'       => now(),
        ]);

        // ✅ Save in campaign_subscribe (agar campaign_id mila ho)
        if ($campaignId) {
            CampaignSubscribe::create([
                'user_id'     => $userId,
                'campaign_id' => $campaignId,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Package successfully purchased & campaign joined',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to save purchase: ' . $e->getMessage(),
        ], 500);
    }
}

}
