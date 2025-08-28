<?php

use Illuminate\Support\Facades\Log;

if (!function_exists('ApiResponse')) {
    function ApiResponse($success, $message, $data = null, $status = 200)
    {

        Log::info("API Response", [
            'status' => $status,
            'success' => $success,
            'message' => $message,
        ]);
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data'    => $data,
        ], $status);
    }
}


function UploadPhoto($photo, $folder = 'images')
{
    // Ensure the photo is a valid uploaded file
    if (!$photo || !$photo->isValid()) {
        return null;
    }

    // Create the folder if it doesn't exist
    $destinationPath = public_path($folder);
    if (!file_exists($destinationPath)) {
        mkdir($destinationPath, 0755, true);
    }

    // Generate a unique name for the image
    $imageName = time() . '_photo.' . $photo->getClientOriginalExtension();

    // Move the uploaded file to the public folder
    $photo->move($destinationPath, $imageName);

    // Return the relative file path
    return '/' . $folder . '/' . $imageName;
}
