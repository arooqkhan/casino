<?php

namespace App\Http\Controllers\AdminController;

use App\Models\Bonus;
use App\Models\Campaign;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BonusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
 public function index()
{
    $bonuses = Bonus::orderBy('created_at','desc')->get();
    return view('admin.pages.bonus.index', compact('bonuses'));
}

    /**
     * Show the form for creating a new resource.
     */
   public function create()
{
    $campaigns = Campaign::all();
    return view('admin.pages.bonus.create', compact('campaigns'));
}

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
    $request->validate([
        'type' => 'required|string',
        'campaign_id' => 'nullable|exists:campaigns,id',
        'valid_from' => 'nullable|date',
        'valid_until' => 'nullable|date|after_or_equal:valid_from',
    ]);

    Bonus::create($request->all());

    return redirect()->route('bonus.index')->with('success', 'Bonus created successfully!');
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
    $bonus = Bonus::findOrFail($id);
    $campaigns = Campaign::all();
    return view('admin.pages.bonus.edit', compact('bonus', 'campaigns'));
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $request->validate([
        'type' => 'required|string',
        'campaign_id' => 'nullable|exists:campaigns,id',
        'valid_from' => 'nullable|date',
        'valid_until' => 'nullable|date|after_or_equal:valid_from',
    ]);

    $bonus = Bonus::findOrFail($id);
    $bonus->update($request->all());

    return redirect()->route('bonus.index')->with('success', 'Bonus updated successfully!');
}
    /**
     * Remove the specified resource from storage.
     */
  public function destroy($id)
{
    $bonus = Bonus::findOrFail($id);
    $bonus->delete();

    return redirect()->route('bonus.index')->with('success', 'Bonus delete!');
}
}
