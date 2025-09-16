<?php

namespace App\Http\Controllers\Api;

use App\Models\Faq;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiFaqController extends Controller
{
    
    public function listApi()
{
    $faqs = Faq::where('status', 'active')->latest()->get();

    return response()->json([
        'success' => true,
        'message' => 'FAQs fetched successfully',
        'data'    => $faqs,
    ]);
}

}
