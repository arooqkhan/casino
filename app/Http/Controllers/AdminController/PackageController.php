<?php

namespace App\Http\Controllers\AdminController;

use App\Models\Package;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $packages = Package::orderBy('created_at', 'desc')->get();

            return view('admin.pages.packages.index', compact('packages'));
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.packages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'price'  => 'required|numeric|min:0',
            'icon'   => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'credit' => 'required|integer|min:0',

            'color'         => 'required|string|max:20',
            'shadow'        => 'required|string|max:255',
        ]);

        try {
            // ✅ Handle image upload
            if ($request->hasFile('icon')) {
                $file = $request->file('icon');
                $filename = 'images/projects/' . time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('images/projects'), $filename);
                $validated['icon'] = $filename; // save path into DB
            }

            // ✅ Save package
            Package::create($validated);

            return redirect()->route('packages.index')
                ->with('success', 'Package created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong: ' . $e->getMessage());
        }
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
        $package = Package::findOrFail($id);

        return view('admin.pages.packages.edit', compact('package'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $package = Package::findOrFail($id);

        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'price'  => 'required|numeric|min:0',
            'icon'   => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'credit' => 'required|integer|min:0',

            'color'         => 'required|string|max:20',
            'shadow'        => 'required|string|max:255',
        ]);

        try {
            // ✅ Agar nayi image upload ho
            if ($request->hasFile('icon')) {
                $file = $request->file('icon');
                $filename = 'images/projects/' . time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('images/projects'), $filename);
                $validated['icon'] = $filename; // save path into DB
            }

            // ✅ Record update karo
            $package->update($validated);

            return redirect()->route('packages.index')
                ->with('success', 'Package updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $package = Package::findOrFail($id);

        try {
            // ✅ Agar package ka icon hai to delete karo
            if ($package->icon && file_exists(public_path($package->icon))) {
                unlink(public_path($package->icon));
            }

            $package->delete();

            return redirect()->route('packages.index')
                ->with('success', 'Package deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}
