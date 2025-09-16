<?php

namespace App\Http\Controllers\AdminController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    public function edit()
    {
        return view('admin.pages.userprofile.index');
    }


    public function update(Request $request)
    {
        $user = Auth::user();

        // Validation
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'image'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Update basic info
        $user->first_name = $request->first_name;
        $user->last_name  = $request->last_name;
        $user->email      = $request->email;

        // Handle profile image
        if ($request->hasFile('image')) {
            // Purani image delete kar do (agar hai)
            if ($user->image && File::exists(public_path($user->image))) {
                File::delete(public_path($user->image));
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $uploadPath = public_path('uploads/profile');

            // Agar folder exist nahi karta to bana lo
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0777, true, true);
            }

            // Save image in uploads/profile
            $image->move($uploadPath, $imageName);

            $user->image = 'uploads/profile/' . $imageName;
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}
