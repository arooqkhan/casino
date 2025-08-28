<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
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
            return ApiResponse(false, "User not authenticated", null, 401);
        }

        return ApiResponse(true, "User profile retrieved successfully", $user);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:50',
            'last_name'  => 'required|string|max:50',
            'dob'        => 'required|date',
            'email'      => 'required|email|unique:users,email',
            'address'    => 'required|string|max:255',
            'password'   => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return ApiResponse(false, "Validation errors", $validator->errors(), 422);
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

            return ApiResponse(true, "User registered successfully", $user, 201);
        } catch (\Exception $e) {
            return ApiResponse(false, "Something went wrong", $e->getMessage(), 500);
        }
    }

    public function login(Request $request)
    {
        Log::info("API Hit: /api/login", $request->only(['email']));

        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            Log::warning("API Validation Failed: /api/login", $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors'  => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $data = [
            'user'  => $user,
            'token' => $token,
        ];

        Log::info("API Response: /api/login", ['status' => 200, 'user_id' => $user->id]);

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data'    => $data
        ], 200);
    }
}
