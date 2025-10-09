<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Card;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;


class ProfileController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        if (!$user) {

            return ApiHelper::sendResponse(false, "User not authenticated", null, 401);
        }

        // ✅ Get all users list
        $users = User::all();

        return ApiHelper::sendResponse(true, "Users retrieved successfully", $users, 200);
    }

  


    public function profile(Request $request)
    {
        $user = Auth::user();


    // ✅ Profile image (fallback if empty)
  $user->image = $user->image ? asset($user->image) : null;

    // ✅ Packages
    $packages = DB::table('package_user')
        ->join('packages', 'package_user.package_id', '=', 'packages.id')
        ->where('package_user.user_id', $user->id)
        ->select('packages.*', 'package_user.created_at as subscribed_at')
        ->get();

    // ✅ Campaigns joined
    $campaigns = DB::table('campaign_subscribe')
        ->join('campaigns', 'campaign_subscribe.campaign_id', '=', 'campaigns.id')
        ->where('campaign_subscribe.user_id', $user->id)
        ->select(
            'campaigns.id',
            'campaigns.name',
            'campaigns.description',
            'campaigns.status',
            'campaigns.winner_price',
            'campaigns.start_at',
            'campaigns.end_at',
            'campaign_subscribe.result as user_result'
        )
        ->get();

    // ✅ Bonuses claimed
  $bonuses = DB::table('bonus_users')
    ->join('bonuses', 'bonus_users.bonus_id', '=', 'bonuses.id')
    ->where('bonus_users.user_id', $user->id)
    ->select(
        'bonuses.id',
        'bonuses.type',
        'bonuses.description',
        'bonuses.valid_until',
        'bonus_users.time as claimed_at'
    )
    ->get()
    ->map(function ($bonus) {
        $bonus->valid_until = $bonus->valid_until 
            ? \Carbon\Carbon::parse($bonus->valid_until)->endOfDay()->toISOString()
            : null;
        return $bonus;
    });

    // ✅ KYC Documents
    $kycDocs = DB::table('user_documents')
        ->where('user_id', $user->id)
        ->select('id', 'document_type', 'document_number', 'file_path', 'status', 'created_at')
        ->get()
        ->map(function ($doc) {
            $doc->file_url = $doc->file_path ? asset($doc->file_path) : null;
            return $doc;
        });

        if (!$user) {
            return ApiHelper::sendResponse(false, "User not authenticated", null, 401);
        }


        // ✅ Profile image (fallback if empty)
        $user->image = $user->image
            ? asset($user->image)
            : asset('uploads/default.png');

        // ✅ Packages
        $packages = DB::table('package_user')
            ->join('packages', 'package_user.package_id', '=', 'packages.id')
            ->where('package_user.user_id', $user->id)
            ->select('packages.*', 'package_user.created_at as subscribed_at')
            ->get();

        // ✅ Campaigns joined
        $campaigns = DB::table('campaign_subscribe')
            ->join('campaigns', 'campaign_subscribe.campaign_id', '=', 'campaigns.id')
            ->where('campaign_subscribe.user_id', $user->id)
            ->select(
                'campaigns.id',
                'campaigns.name',
                'campaigns.description',
                'campaigns.status',
                'campaigns.winner_price',
                'campaigns.start_at',
                'campaigns.end_at',
                'campaign_subscribe.result as user_result'
            )
            ->get();

        // ✅ Bonuses claimed
        $bonuses = DB::table('bonus_users')
            ->join('bonuses', 'bonus_users.bonus_id', '=', 'bonuses.id')
            ->where('bonus_users.user_id', $user->id)
            ->select(
                'bonuses.id',
                'bonuses.type',
                'bonuses.description',
                'bonuses.valid_until',
                'bonus_users.time as claimed_at'
            )
            ->get()
            ->map(function ($bonus) {
                $bonus->valid_until = $bonus->valid_until
                    ? \Carbon\Carbon::parse($bonus->valid_until)->endOfDay()->toISOString()
                    : null;
                return $bonus;
            });

        // ✅ KYC Documents
        $kycDocs = DB::table('user_documents')
            ->where('user_id', $user->id)
            ->select('id', 'document_type', 'document_number', 'file_path', 'status', 'created_at')
            ->get()
            ->map(function ($doc) {
                $doc->file_url = $doc->file_path ? asset($doc->file_path) : null;
                return $doc;
            });

        // ✅ Bank/Card details
        $bankDetails = Card::where('user_id', $user->id)->first();

        // ✅ Payment history
        $paymentHistory = DB::table('transaction_histories')
            ->where('user_id', $user->id)

            ->orderBy('created_at', 'desc')
            ->get();

        // ✅ Response payload
        $data = [
            'user'            => $user,
            'packages'        => $packages,
            'campaigns'       => $campaigns,
            'bonuses'         => $bonuses,
            'kyc_docs'        => $kycDocs,
            'bank_details'    => $bankDetails,
            'payment_history' => $paymentHistory, // 🆕 added
        ];

        return ApiHelper::sendResponse(true, "User retrieved successfully", $data, 200);
    }







    public function show($id)
    {
        $user = Auth::user();

        if (!$user) {
            return ApiHelper::sendResponse(false, "User not authenticated", null, 401);
        }

        // ✅ Find user by ID
        $userData = User::find($id);

        if (!$userData) {
            return ApiHelper::sendResponse(false, "User not found", null, 404);
        }

        return ApiHelper::sendResponse(true, "User retrieved successfully", $userData, 200);
    }

    public function register(Request $request)
    {
        $validation = ApiHelper::validate($request->all(), [
            'first_name' => 'required|string|max:50',
            'last_name'  => 'required|string|max:50',
            'dob'        => 'nullable|date',
            'email'      => 'required|email|unique:users,email',
            'address'    => 'nullable|string|max:255',
            'password'   => 'required|string|min:6',
        ]);

        if (!$validation['success']) {
            return ApiHelper::sendResponse(false, "Validation errors", $validation['errors'], 422);
        }

        try {
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name,
                'dob'        => $request->dob ?? null,
                'email'      => $request->email,
                'address'    => $request->address ?? null,
                'password'   => Hash::make($request->password),
            ]);

            // Email verification link bhejna
            $user->sendEmailVerificationNotification();

            return ApiHelper::sendResponse(true, "User registered successfully. Please check your email for verification link.", [], 201);
        } catch (\Exception $e) {
            return ApiHelper::sendResponse(false, "Something went wrong", $e->getMessage(), 500);
        }
    }




    public function login(Request $request)
    {
        Log::info("API Hit: /api/login", $request->only(['email']));

        // ✅ Validation
        $validation = ApiHelper::validate($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if (!$validation['success']) {
            Log::warning("API Validation Failed: /api/login", $validation['errors']->toArray());
            return ApiHelper::sendResponse(false, 'Validation errors', [
                'errors' => $validation['errors']
            ], 422);
        }

        // ✅ User check
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return ApiHelper::sendResponse(false, 'Invalid credentials', [], 401);
        }

        // ✅ Email verification check
        if (is_null($user->email_verified_at)) {
            Log::warning("Login blocked: Email not verified", ['user_id' => $user->id]);
            return ApiHelper::sendResponse(false, 'Please verify your email before logging in.', [], 403);
        }

        // ✅ Token generate
        $token = $user->createToken('auth_token')->plainTextToken;

        // Merge token directly with user attributes
        $userData = $user->toArray();
        $userData['token'] = $token;

        Log::info("API Response: /api/login", ['status' => 200, 'user_id' => $user->id]);

        return ApiHelper::sendResponse(true, 'Login successful', $userData, 200);
    }



    // Forgot Password - Send OTP
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return ApiHelper::sendResponse(false, $validator->errors()->first(), null, 422);
        }

        $otp = rand(100000, 999999); // 6-digit OTP
        $user = User::where('email', $request->email)->first();
        $user->otp = $otp;
        $user->otp_expires_at = now()->addMinutes(10); // OTP valid for 10 mins
        $user->save();

        // Send OTP via Email (you can replace with SMS API)
        Mail::raw("Your OTP is: $otp", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Password Reset OTP');
        });

        return ApiHelper::sendResponse(true, "OTP sent to your email", null, 200);
    }

    // Verify OTP
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'otp'   => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return ApiHelper::sendResponse(false, $validator->errors()->first(), null, 422);
        }

        $user = User::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('otp_expires_at', '>=', now())
            ->first();

        if (!$user) {
            return ApiHelper::sendResponse(false, "Invalid or expired OTP", null, 400);
        }

        return ApiHelper::sendResponse(true, "OTP verified successfully", null, 200);
    }

    // Reset Password
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email|exists:users,email',
            'otp'      => 'required|numeric',
            'password' => 'required|min:6|confirmed', // must send password + password_confirmation
        ]);

        if ($validator->fails()) {
            return ApiHelper::sendResponse(false, $validator->errors()->first(), null, 422);
        }

        $user = User::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('otp_expires_at', '>=', now())
            ->first();

        if (!$user) {
            return ApiHelper::sendResponse(false, "Invalid or expired OTP", null, 400);
        }

        $user->password = Hash::make($request->password);
        $user->otp = null; // clear OTP
        $user->otp_expires_at = null;
        $user->save();

        return ApiHelper::sendResponse(true, "Password reset successfully", null, 200);
    }



    // Update profile api

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return ApiHelper::sendResponse(false, "User not authenticated", null, 401);
        }

        try {
            // Validation (aapke screenshot wale fields ke liye)
            $request->validate([
                'first_name'         => 'nullable|string|max:255',
                'last_name'          => 'nullable|string|max:255',
                'dob'                => 'nullable|date',
                'address'            => 'nullable|string|max:255',
                'email'              => 'nullable|email|unique:users,email,' . $user->id,
                'image'              => 'nullable|image|max:2048',
                'province'           => 'nullable|string|max:255',
                'postal_code'        => 'nullable|string|max:255',
                'city'               => 'nullable|string|max:255',
                'country'            => 'nullable|string|max:255',
            ]);

            // Saare input ek saath le lo
            $data = $request->all();

            // Password hash karna hoga
            if ($request->filled('password')) {
                $data['password'] = bcrypt($request->password);
            }

            // Profile image handle
            if ($request->hasFile('image')) {
                if ($user->image && File::exists(public_path($user->image))) {
                    File::delete(public_path($user->image));
                }

                $image = $request->file('image');
                $imageName = 'profile_' . time() . '.' . $image->getClientOriginalExtension();
                $uploadPath = public_path('uploads/profile');

                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0777, true, true);
                }

                $image->move($uploadPath, $imageName);
                $data['image'] = 'uploads/profile/' . $imageName;
            }

            // Avatar image handle
            if ($request->hasFile('avatar')) {
                if ($user->avatar && File::exists(public_path($user->avatar))) {
                    File::delete(public_path($user->avatar));
                }

                $avatar = $request->file('avatar');
                $avatarName = 'avatar_' . time() . '.' . $avatar->getClientOriginalExtension();
                $uploadPath = public_path('uploads/avatar');

                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0777, true, true);
                }

                $avatar->move($uploadPath, $avatarName);
                $data['avatar'] = 'uploads/avatar/' . $avatarName;
            }

            // User update with all request data
            $user->update($data);

            // Full URLs for response
            if ($user->image) {
                $user->image = asset($user->image);
            }
            if ($user->avatar) {
                $user->avatar = asset($user->avatar);
            }

            return ApiHelper::sendResponse(true, "Profile updated successfully", $user, 200);
        } catch (\Exception $e) {
            return ApiHelper::sendResponse(false, "Something went wrong", $e->getMessage(), 500);
        }
    }
}
