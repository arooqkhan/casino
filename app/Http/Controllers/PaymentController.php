<?php

namespace App\Http\Controllers;

use App\Models\TransactionHistory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\Checkout\Session;

class PaymentController extends Controller
{
    public function checkout(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'method' => 'required|string',
            'type'   => 'required|in:credit,debit'
        ]);

        // Stripe Deposits
        if ($request->method === 'stripe' && $request->type === 'credit') {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            try {
                $session = Session::create([
                    'payment_method_types' => ['card'],
                    'line_items' => [[
                        'price_data' => [
                            'currency' => 'usd',
                            'product_data' => ['name' => 'Deposit'],
                            'unit_amount' => $request->amount * 100, // cents
                        ],
                        'quantity' => 1,
                    ]],
                    'mode' => 'payment',
                    'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url'  => route('payment.cancel'),
                ]);

                return redirect($session->url, 303);
            } catch (Exception $e) {
                return back()->with('error', 'Stripe error: ' . $e->getMessage());
            }
        }

        // Handle Withdrawals locally
        if ($request->type === 'debit') {
            if ($user->balance < $request->amount) {
                return back()->with('error', 'Insufficient balance for withdrawal.');
            }

            DB::beginTransaction();
            try {
                $user->balance -= $request->amount;
                $user->save();

                TransactionHistory::create([
                    'user_id'        => $user->id,
                    'type'           => 'withdraw',
                    'amount'         => $request->amount,
                    'status'         => 0, // pending
                    'trans_type'     => $request->method,
                    'payment_status' => 'pending'
                ]);

                DB::commit();
                return back()->with('success', 'Withdrawal request submitted for approval.');
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'Transaction failed: ' . $e->getMessage());
            }
        }

        return back()->with('error', 'Invalid request.');
    }

    /**
     * Success callback (after redirect from Stripe)
     */
    public function success(Request $request)
    {
        $user = Auth::user();
        $sessionId = $request->query('session_id');

        if (!$sessionId) {
            return redirect()->route('dashboard')->with('error', 'No session ID found.');
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $session = Session::retrieve($sessionId);

            if ($session->payment_status === 'paid') {
                DB::beginTransaction();

                $user->balance += ($session->amount_total / 100);
                $user->save();

                TransactionHistory::create([
                    'user_id'        => $user->id,
                    'type'           => 'deposit',
                    'amount'         => $session->amount_total / 100,
                    'status'         => 1, // success
                    'trans_type'     => 'stripe',
                    'payment_status' => 'approved',
                ]);

                DB::commit();

                return redirect()->route('dashboard')
                    ->with('success', 'Deposit successful!');
            }

            return redirect()->route('dashboard')
                ->with('error', 'Payment not completed.');
        } catch (\Exception $e) {
            return redirect()->route('dashboard')
                ->with('error', 'Stripe verification failed: ' . $e->getMessage());
        }
    }

    /**
     * Cancel callback (after Stripe cancel)
     */
    public function cancel()
    {
        return redirect()->route('dashboard')->with('error', 'Payment was cancelled.');
    }

    /**
     * Stripe Webhook (recommended way to log payments)
     */
    public function webhook(Request $request)
    {
        $endpointSecret = env('STRIPE_WEBHOOK_SECRET'); // set in .env
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\UnexpectedValueException $e) {
            return response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response('Invalid signature', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            $user = Auth::where('email', $session->customer_email)->first();
            if ($user) {
                DB::beginTransaction();
                try {
                    $user->balance += ($session->amount_total / 100);
                    $user->save();

                    TransactionHistory::create([
                        'user_id'        => $user->id,
                        'type'           => 'deposit',
                        'amount'         => $session->amount_total / 100,
                        'status'         => 1,
                        'trans_type'     => 'stripe',
                        'payment_status' => 'approved',
                        'transaction_id' => $session->payment_intent ?? null,
                    ]);

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                }
            }
        }

        return response('Webhook handled', 200);
    }

    // public function checkout(Request $request)
    // {
    //     $user = Auth::user();

    //     $request->validate([
    //         'amount' => 'required|numeric|min:1',
    //         'method' => 'required|string',
    //         'type'   => 'required|in:credit,debit'
    //     ]);

    //     // Handle Stripe deposits
    //     if ($request->method === 'stripe' && $request->type === 'credit') {
    //         Stripe::setApiKey(env('STRIPE_SECRET'));

    //         try {
    //             $session = Session::create([
    //                 'payment_method_types' => ['card'],
    //                 'line_items' => [[
    //                     'price_data' => [
    //                         'currency' => 'usd',
    //                         'product_data' => ['name' => 'Deposit'],
    //                         'unit_amount' => $request->amount * 100, // cents
    //                     ],
    //                     'quantity' => 1,
    //                 ]],
    //                 'mode' => 'payment',
    //                 'success_url' => route('payment.success', ['amount' => $request->amount]),
    //                 'cancel_url'  => route('payment.cancel'),
    //             ]);

    //             return redirect($session->url, 303);
    //         } catch (Exception $e) {
    //             return back()->with('error', 'Stripe error: ' . $e->getMessage());
    //         }
    //     }

    //     // Handle withdrawals locally
    //     if ($request->type === 'debit') {
    //         if ($user->balance < $request->amount) {
    //             return back()->with('error', 'Insufficient balance for withdrawal.');
    //         }

    //         DB::beginTransaction();
    //         try {
    //             $user->balance -= $request->amount;
    //             $user->save();

    //             TransactionHistory::create([
    //                 'user_id'        => $user->id,
    //                 'type'           => 'withdraw',
    //                 'amount'         => $request->amount,
    //                 'status'         => 0, // pending
    //                 'trans_type'     => $request->method,
    //                 'payment_status' => 'pending'
    //             ]);

    //             DB::commit();
    //             return back()->with('success', 'Withdrawal request submitted for approval.');
    //         } catch (\Exception $e) {
    //             DB::rollBack();
    //             return back()->with('error', 'Transaction failed: ' . $e->getMessage());
    //         }
    //     }

    //     return back()->with('error', 'Invalid request.');
    // }


    // public function checkout(Request $request)
    // {
    //     dd($request->all());
    //     Stripe::setApiKey(env('STRIPE_SECRET'));

    //     try {
    //         $session = Session::create([
    //             'payment_method_types' => ['card'],
    //             'line_items' => [[
    //                 'price_data' => [
    //                     'currency' => 'usd',
    //                     'product_data' => [
    //                         'name' => 'Laravel Stripe Payment',
    //                     ],
    //                     'unit_amount' => 1000, // $10.00 in cents
    //                 ],
    //                 'quantity' => 1,
    //             ]],
    //             'mode' => 'payment',
    //             'success_url' => route('payment.success'),
    //             'cancel_url' => route('payment.cancel'),
    //         ]);

    //         return redirect($session->url, 303);
    //     } catch (Exception $e) {
    //         return back()->withErrors(['error' => 'Unable to create payment session: ' . $e->getMessage()]);
    //     }
    // }
}
