<?php

namespace App\Http\Controllers\AdminController;

use App\Models\ContactUs;
use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContactUsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $contacts = ContactUs::latest()->paginate(10);

    return view('admin.pages.contactus.index', compact('contacts'));
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
        'name'      => 'required|string|max:100',
        'email'     => 'required|email|max:150',
        'type_of_issue'      => 'required|string|max:50',
        'subject'   => 'required|string|max:150',
        'message'   => 'required|string|max:1000',
    ]);

    // Save to DB
    $contact = ContactUs::create([
        'name'          => $request->name,
        'email'         => $request->email,
        'type_of_issue' => $request->type_of_issue,  
        'subject'       => $request->subject,
        'message'       => $request->message,
    ]);

    return ApiHelper::sendResponse(
        true, 
        "Your message has been submitted successfully!", 
        $contact
    );
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
   public function destroy(string $id)
{


    $contact = ContactUs::findOrFail($id);
    $contact->delete();

    return redirect()->back()->with('success', 'Contact message deleted successfully.');
}


}
