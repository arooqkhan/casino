<?php

namespace App\Http\Controllers\AdminController;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       
        $users = User::orderBy('created_at','desc')->get();

        return view('admin.pages.users.index',compact('users'));
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */




    public function store(Request $request)
    {
     
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'dob'        => 'nullable|date',
            'email'      => 'required|email|unique:users,email',
            'address'    => 'nullable|string',
       
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('public/profile_images', $imageName);
            $validatedData['image'] = $imageName;
        }

        // Optionally, you can set a default password (hashed)
        $validatedData['password'] = Hash::make('defaultpassword'); 

        // Create the user
        $user = User::create($validatedData);

        return redirect()->route('users.index')->with('success', 'User created successfully!');
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
   public function edit($id)
{
    $user = User::findOrFail($id);
    return view('admin.pages.users.edit', compact('user'));
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Find the user
        $user = User::findOrFail($id);

        // Validate the incoming request
        $validatedData = $request->validate([
            'first_name' => 'sometimes|string|max:255',
            'last_name'  => 'sometimes|string|max:255',
            'dob'        => 'nullable|date',
            'email'      => 'sometimes|email|unique:users,email,' . $user->id,
            'password'   => 'nullable|string|min:6', // optional: only update if provided
            'address'    => 'nullable|string',
        ]);

        // Hash password if provided
        if (!empty($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            // Remove password from data so it won't overwrite existing
            unset($validatedData['password']);
        }

        // Update the user
        $user->update($validatedData);

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User Deleted!');
    }
}
