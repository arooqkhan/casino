<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Validator;

class ApiHelper
{
    public static function validate($data, $rules)
    {
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return [
                'success' => false,
                'errors'  => $validator->errors(),
                'status'  => 422
            ];
        }

        return [
            'success' => true,
            'errors'  => null,
            'status'  => 200
        ];
    }

    public static function sendResponse($success, $message, $data = [], $status = 200)
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data'    => $data
        ], $status);
    }
}
