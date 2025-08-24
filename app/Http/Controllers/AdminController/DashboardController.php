<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use App\Models\TransactionHistory;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;


class DashboardController extends Controller
{
public function dashboard()
{
    // Row 1
    $totalamount    = TransactionHistory::sum('amount');
    $withdrawamount = TransactionHistory::where('type','withdraw')->sum('amount');         
    $depositamount  = TransactionHistory::where('type','deposit')->sum('amount');         

    // Current month (day-wise) chart data
    $start   = Carbon::now()->startOfMonth();
    $end     = Carbon::now()->endOfMonth();
    $period  = CarbonPeriod::create($start, $end);

    $chartLabels  = [];
    $depositData  = [];
    $withdrawData = [];

    foreach ($period as $day) {
        $chartLabels[] = $day->format('M d');

        $depositData[] = TransactionHistory::where('type','deposit')
            ->whereDate('created_at', $day)
            ->sum('amount');

        $withdrawData[] = TransactionHistory::where('type','withdraw')
            ->whereDate('created_at', $day)
            ->sum('amount');
    }

    return view('dashboard', compact(
        'totalamount',
        'withdrawamount',
        'depositamount',
        'chartLabels',
        'depositData',
        'withdrawData'
    ));
}



    
}
