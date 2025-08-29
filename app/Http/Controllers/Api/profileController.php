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
        'dob'        => 'required|date',
        'email'      => 'required|email|unique:users,email',
        'address'    => 'required|string|max:255',
        'password'   => 'required|string|min:6',
    ]);

    if (!$validation['success']) {
        return ApiHelper::sendResponse(false, "Validation errors", $validation['errors'], 422);
    }

    try {
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'dob'        => $request->dob,
            'email'      => $request->email,
            'address'    => $request->address,
            'password'   => Hash::make($request->password),
        ]);

        return ApiHelper::sendResponse(true, "User registered successfully", $user, 201);

    } catch (\Exception $e) {
        return ApiHelper::sendResponse(false, "Something went wrong", $e->getMessage(), 500);
    }
}

   public function login(Request $request)
{
    Log::info("API Hit: /api/login", $request->only(['email']));

    // ✅ Validation via Helper
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

    $data = [
        'user'  => $user,
        'token' => $token,
    ];

    Log::info("API Response: /api/login", ['status' => 200, 'user_id' => $user->id]);

    return ApiHelper::sendResponse(true, 'Login successful', $data, 200);
}
}
