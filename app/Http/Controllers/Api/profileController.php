<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller; // ✅ Correct import

use App\Models\UserDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
