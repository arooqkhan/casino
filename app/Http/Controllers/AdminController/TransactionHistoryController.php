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


    // public function approve(Request $request, $id)
    // {
    //     $user = auth()->user();
    //     $user->balance -= $request->amount;
    //     $user->save();

    //     $transaction = TransactionHistory::findOrFail($id);
    //     $transaction->update([
    //         'payment_status' => 'approved',
    //         'status' => 1,
    //         'is_sent' => 1,
    //     ]);

    //     return back()->with('success', 'Withdrawal approved and marked as sent!');
    // }

    public function approve(Request $request, $id)
    {
        $transaction = TransactionHistory::findOrFail($id);

        // Get the user who made the transaction
        $user = $transaction->user; // assuming you have a relation: TransactionHistory belongsTo User

        // Deduct balance
        $user->balance -= $transaction->amount;
        $user->save();

        // Update transaction status
        $transaction->update([
            'payment_status' => 'approved',
            'status' => 1,
            'is_sent' => 1,
        ]);

        return back()->with('success', 'Withdrawal approved and marked as sent!');
    }

    public function reject(Request $request, $id)
    {
        $user = auth()->user();
        $user->balance += $request->amount;
        $user->save();
        $transaction = TransactionHistory::findOrFail($id);
        $transaction->update([
            'payment_status' => 'pending', // Or maybe 'rejected' if you add that
            'status' => 2,
            'is_sent' => 2,
        ]);

        return back()->with('error', 'Withdrawal rejected!');
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
