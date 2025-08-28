<?php

namespace App\Http\Controllers\Api;

use App\Models\User;

use App\Models\UserDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller; // ✅ Correct import

class profileController extends Controller
{
    /**
     * Display the authenticated user's profile and documents.
     */
    public function index()
    {
        $user = Auth::user(); // Get the currently authenticated user

        if (!$user) {
            return ApiResponse(false, "User not authenticated", null, 401);
        }

        // Load relationships if needed
        // $user->load('documents'); // Eager load 'documents'

        return ApiResponse(true, "User profile retrieved successfully", $user);
    }

    public function register(Request $request)
{
    // Validation
    $validator = Validator::make($request->all(), [
        'first_name' => 'required|string|max:50',
        'last_name'  => 'required|string|max:50',
        'dob'        => 'required|date',
        'email'      => 'required|email|unique:users,email',
        'address'    => 'required|string|max:255',
        'password'   => 'required|string|min:6', // password bhi zaroori hai
    ]);

    if ($validator->fails()) {
        return ApiResponse(false, "Validation errors", $validator->errors(), 422);
    }

    try {
        // User create
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
   
    $validator = Validator::make($request->all(), [
        'email'    => 'required|email',
        'password' => 'required|string|min:6',
    ]);

    if ($validator->fails()) {
        return ApiResponse(false, "Validation errors", $validator->errors(), 422);
    }

    // User find
    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return ApiResponse(false, "Invalid credentials", null, 401);
    }

    // Token generate (Sanctum)
    $token = $user->createToken('auth_token')->plainTextToken;

    $data = [
        'user'  => $user,
        'token' => $token,
    ];

    return ApiResponse(true, "Login successful", $data, 200);
}




}
