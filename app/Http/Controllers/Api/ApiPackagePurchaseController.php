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
use Illuminate\Support\Facades\Auth;

class ApiPackagePurchaseController extends Controller
{
    public function createCheckout(Request $request)
    {
        $validated = $request->validate([
            'package_id' => 'required|exists:packages,id',

        ]);

        $user = Auth::user()->id;

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
                'success_url' => url('/api/stripe/success?session_id={CHECKOUT_SESSION_ID}&package_id=' . $package->id . '&user_id=' . $user),
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

  public function joinCampaign(Request $request)
{
    $userId = Auth::user()->id;
    $package = PackageUser::where('user_id', $userId)->first();

    if (!empty($package)) {

        // ✅ campaign_id from body instead of query
        $campaignId = $request->input('campaign_id'); 

        try {
           
            // ✅ Save in campaign_subscribe (if campaign_id exists in body)
            if ($campaignId) {
                $compaign =CampaignSubscribe::create([
                    'user_id'     => $userId,
                    'campaign_id' => $campaignId,
                ]);
            }

        

            return ApiHelper::sendResponse(true, "Package successfully campaign joined", $compaign, 200);

        } catch (\Exception $e) {
            return ApiHelper::sendResponse(false, "Failed to Joining campanign:", $e->getMessage(), 500);
        }
    } else {
        return ApiHelper::sendResponse(false, "Package Not Found", '', 404);
    }
}


 public function getAllCompaign()
    {
        // Get all users with their subscribed campaigns
        $users = User::with(['campaigns' => function ($query) {
            $query->select('campaigns.id', 'campaigns.name', 'campaigns.status');
        }])->get(['id', 'first_name','last_name', 'email']);

       return ApiHelper::sendResponse(true, "Campaign list", $users);
    }

}
