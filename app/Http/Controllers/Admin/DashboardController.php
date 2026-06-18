<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\Transaction;
use App\Models\QueueMonitor;
use App\Models\ServicePackage;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = today();

        $stats = [
            'total_customers' => User::where('role', 'customer')->count(),
            'today_bookings' => Booking::whereDate('scheduled_at', $today)->count(),
            'today_revenue' => Transaction::whereDate('paid_at', $today)->where('status', 'success')->sum('amount'),
            'in_progress' => Booking::where('status', 'in_progress')->count(),
            'monthly_revenue' => Transaction::whereMonth('paid_at', $today->month)
                ->whereYear('paid_at', $today->year)
                ->where('status', 'success')
                ->sum('amount'),
            'completed_today' => Booking::whereDate('scheduled_at', $today)->where('status', 'completed')->count(),
            'pending_today' => Booking::whereDate('scheduled_at', $today)->whereIn('status', ['pending', 'confirmed', 'in_queue'])->count(),
        ];

        $queueMonitor = QueueMonitor::today();

        $todayBookings = Booking::whereDate('scheduled_at', $today)
            ->with(['user', 'vehicle', 'servicePackage'])
            ->orderBy('queue_number')
            ->get();

        // Revenue last 7 days
        $revenueChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $revenueChart[] = [
                'date' => $date->format('d M'),
                'revenue' => Transaction::whereDate('paid_at', $date)->where('status', 'success')->sum('amount'),
                'count' => Booking::whereDate('scheduled_at', $date)->where('status', 'completed')->count(),
            ];
        }

        // Package popularity
        $packageStats = ServicePackage::withCount(['bookings' => function ($q) use ($today) {
            $q->whereMonth('created_at', $today->month);
        }])->orderByDesc('bookings_count')->get();

        $recentTransactions = Transaction::with(['booking.user', 'booking.servicePackage'])
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 'queueMonitor', 'todayBookings',
            'revenueChart', 'packageStats', 'recentTransactions'
        ));
    }
}
