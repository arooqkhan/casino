<?php

namespace App\Http\Controllers\AdminController;

use Illuminate\Http\Request;
use App\Models\TransactionHistory;
use App\Http\Controllers\Controller;

class TransactionHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
{
    $transaction_histories = TransactionHistory::with('user')
        ->orderBy('created_at', 'desc')
        ->get();

    return view('admin.pages.transaction_history.index', compact('transaction_histories'));
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
        //
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
        //
    }
}
