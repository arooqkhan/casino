<?php


function ApiResponse($status = true, $message = '', $data = null, $code = 200)
{
    return response()->json([
        'success' => $status,
        'message' => $message,
        'data' => $data
    ], $code);
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
