<?php

namespace App\Http\Controllers\Api;

use App\Models\Card;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CardController extends Controller
{
   public function update(Request $request)
{
    // Get the currently logged-in user
    $user = Auth::user();

    // Find the card record that belongs to this user
    $card = Card::where('user_id', $user->id)->first();

    if (!$card) {
        return response()->json([
            'success' => false,
            'message' => 'No card found for this user.',
        ], 404);
    }

    // Validate input
    $validated = $request->validate([
        'card_holder_name' => 'required|string|max:255',
        'card_number'      => 'required|string|max:20',
        'expiry_date'      => 'required|date',
        'ccv_code'         => 'required|string|max:4',
        'first_name'       => 'required|string|max:100',
        'last_name'        => 'required|string|max:100',
        'email'            => 'required|email|max:255',
        'province'         => 'nullable|string|max:255',
        'postal_code'      => 'nullable|string|max:20',
        'city'             => 'nullable|string|max:255',
        'country'          => 'nullable|string|max:255',
    ]);

    // Update only this user’s card
    $card->update($validated);

    return response()->json([
        'success' => true,
        'message' => 'Card details updated successfully.',
        'data' => $card,
    ]);
}

}
