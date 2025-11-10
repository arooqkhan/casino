<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $campaigns = Campaign::orderBy('created_at', 'desc')->get();

        return view('admin.pages.campaign.index', compact('campaigns'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.campaign.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'name'          => 'required|string|max:255',
    //         'description'   => 'nullable|string',
    //         'status'        => 'required|in:active,upcoming,expired',
    //         'start_at'      => 'nullable|date',
    //         'end_at'        => 'nullable|date|after_or_equal:start_at',
    //         'countdown_end' => 'nullable|date|after_or_equal:start_at',
    //         'terms'         => 'nullable|string',
    //         'winner_price'         => 'nullable',
    //         'credit'         => 'nullable',

    //         // ✅ new fields
    //         'color'         => 'required|string|max:20',
    //         'shadow'        => 'required|string|max:255',
    //     ]);

    //     Campaign::create($validated);

    //     return redirect()->route('campaigns.index')
    //         ->with('success', 'Campaign created successfully.');
    // }




    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'status'        => 'required|in:active,upcoming,expired',
            'start_at'      => 'nullable|date',
            'end_at'        => 'nullable|date|after_or_equal:start_at',
            'countdown_end' => 'nullable|date|after_or_equal:start_at',
            'terms'         => 'nullable|string',
            'winner_price'  => 'nullable|numeric|min:0',
            'credit'        => 'nullable|numeric|min:0',

            // ✅ new fields
            'color'         => 'required|string|max:20',
            'shadow'        => 'required|string|max:255',
        ]);

        // ✅ Parse dates safely
        $startAt = !empty($request->start_at) ? Carbon::parse($request->start_at) : null;
        $endAt   = !empty($request->end_at) ? Carbon::parse($request->end_at) : null;
        $now     = Carbon::now();

        // ✅ Extra logic by campaign status
        if ($request->status === 'upcoming') {
            // Must not have past dates
            if (($startAt && $startAt->isPast()) || ($endAt && $endAt->isPast())) {
                return back()->withErrors([
                    'start_at' => 'Upcoming campaigns cannot have past dates.'
                ])->withInput();
            }
        }

        if ($request->status === 'expired') {
            // Must have both dates in the past
            if (($startAt && $startAt->isFuture()) || ($endAt && $endAt->isFuture())) {
                return back()->withErrors([
                    'end_at' => 'Expired campaigns must have both start and end dates in the past.'
                ])->withInput();
            }
        }

        if ($request->status === 'active') {
            // Start should be past, end should be future
            if (($startAt && !$startAt->isPast()) || ($endAt && !$endAt->isFuture())) {
                return back()->withErrors([
                    'status' => 'Active campaigns must have started and not yet ended.'
                ])->withInput();
            }
        }

        // ✅ All checks passed, create campaign
        Campaign::create($validated);

        return redirect()->route('campaigns.index')
            ->with('success', 'Campaign created successfully.');
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
        $campaign = Campaign::findOrFail($id);
        return view('admin.pages.campaign.edit', compact('campaign'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'status'        => 'required|in:active,upcoming,expired',
            'start_at'      => 'nullable|date',
            'end_at'        => 'nullable|date|after_or_equal:start_at',
            'countdown_end' => 'nullable|date|after_or_equal:start_at',
            'terms'         => 'nullable|string',
            'winner_price'         => 'nullable',
            'credit'         => 'nullable',

            // ✅ new fields
            'color'         => 'required|string|max:20',
            'shadow'        => 'required|string|max:255',
        ]);

        $campaign = Campaign::findOrFail($id);
        $campaign->update($validated);

        return redirect()->route('campaigns.index')
            ->with('success', 'Campaign updated successfully.');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $campaign = Campaign::findOrFail($id);
        $campaign->delete();

        return redirect()->route('campaigns.index')->with('success', 'Campaign deleted successfully.');
    }
}
