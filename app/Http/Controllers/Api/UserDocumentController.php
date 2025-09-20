<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use App\Models\UserDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\TransactionHistory;
use Illuminate\Support\Facades\File;

class UserDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'document_type'   => 'required|in:cnic_front,cnic_back,passport,driving_license,utility_bill,other',
            'document_number' => 'nullable|string|max:255',
            'file'            => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB
        ]);

        try {
            $user = Auth::user();

            if (!$user) {
                return ApiHelper::sendResponse(false, "User not authenticated", null, 401);
            }

            $filePath = null;

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $uploadPath = public_path('uploads/user_docs');

                // Ensure folder exists
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0777, true, true);
                }

                // Move file
                $file->move($uploadPath, $fileName);

                // Save relative path for DB
                $filePath = 'uploads/user_docs/' . $fileName;
            }

            $doc = UserDocument::create([
                'user_id'         => $user->id,
                'document_type'   => $request->document_type,
                'document_number' => $request->document_number,
                'file_path'       => $filePath,
                'status'          => 'pending',
            ]);

            return ApiHelper::sendResponse(true, "KYC document uploaded successfully", $doc, 201);
        } catch (\Exception $e) {
            return ApiHelper::sendResponse(false, "Something went wrong", $e->getMessage(), 500);
        }
    }
}
