<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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

        if (!$user) {
            return ApiHelper::sendResponse(false, "User not authenticated", null, 401);
        }

        return ApiHelper::sendResponse(true, "User retrieved successfully", $user, 200);
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
        // ✅ Validation via ApiHelper
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
            // ✅ Create user
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name,
                'dob'        => $request->dob ?? null,       // nullable
                'email'      => $request->email,
                'address'    => $request->address ?? null,   // nullable
                'password'   => Hash::make($request->password),
            ]);

            // ✅ Generate token
            $token = $user->createToken('auth_token')->plainTextToken;

            // ✅ Merge token with user data
            $userData = $user->toArray();
            $userData['token'] = $token;

            return ApiHelper::sendResponse(true, "User registered successfully", $userData, 201);
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

        // ✅ Token generate
        $token = $user->createToken('auth_token')->plainTextToken;

        // Merge token directly with user attributes
        $userData = $user->toArray(); // convert Eloquent object to array
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



    // Update profile

    public function updateProfile(Request $request)
{
    $user = Auth::user();

    if (!$user) {
        return ApiHelper::sendResponse(false, "User not authenticated", null, 401);
    }

    try {
        // request se sabhi fields lo except system fields (jaise id, password, created_at, updated_at etc.)
        $data = $request->except(['id', 'password', 'created_at', 'updated_at', 'deleted_at', 'email_verified_at']);

        // update user
        $user->fill($data);
        $user->save();

        return ApiHelper::sendResponse(true, "Profile updated successfully", $user, 200);

    } catch (\Exception $e) {
        return ApiHelper::sendResponse(false, "Something went wrong", $e->getMessage(), 500);
    }
}


}
