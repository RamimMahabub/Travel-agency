<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_sales' => 0,
            'bookings' => 0,
            'profit' => 0,
            'refunds' => 0,
            'pending_tickets' => 0,
            'failed_payments' => 0,
            'active_customers' => 0,
        ];

        if (Schema::hasTable('bookings')) {
            $stats['bookings'] = DB::table('bookings')->count();
            $stats['pending_tickets'] = DB::table('bookings')->whereIn('status', ['pending', 'processing'])->count();
        }

        if (Schema::hasTable('payments')) {
            $stats['total_sales'] = (float) DB::table('payments')
                ->whereIn('status', ['paid', 'completed', 'success'])
                ->sum('amount');

            $stats['failed_payments'] = DB::table('payments')
                ->whereIn('status', ['failed', 'error'])
                ->count();
        }

        if (Schema::hasTable('refunds')) {
            $stats['refunds'] = (float) DB::table('refunds')->sum('amount');
        }

        // Placeholder profit model: sales - refunds.
        $stats['profit'] = $stats['total_sales'] - $stats['refunds'];

        if (Schema::hasTable('users')) {
            $stats['active_customers'] = DB::table('users')->where('role', 'customer')->count();
        }

        return view('admin.dashboard', [
            'stats' => $stats,
        ]);
    }
}
