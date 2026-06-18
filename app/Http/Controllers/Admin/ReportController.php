<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Transaction;
use App\Models\ServicePackage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->period ?? 'monthly';
        $year = $request->year ?? date('Y');
        $month = $request->month ?? date('m');

        if ($period === 'monthly') {
            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate = Carbon::create($year, $month, 1)->endOfMonth();
        } elseif ($period === 'yearly') {
            $startDate = Carbon::create($year, 1, 1)->startOfYear();
            $endDate = Carbon::create($year, 12, 31)->endOfYear();
        } else {
            $startDate = Carbon::now()->startOfWeek();
            $endDate = Carbon::now()->endOfWeek();
        }

        $summary = [
            'total_bookings' => Booking::whereBetween('scheduled_at', [$startDate, $endDate])->count(),
            'completed_bookings' => Booking::whereBetween('scheduled_at', [$startDate, $endDate])->where('status', 'completed')->count(),
            'cancelled_bookings' => Booking::whereBetween('scheduled_at', [$startDate, $endDate])->where('status', 'cancelled')->count(),
            'total_revenue' => Transaction::whereBetween('paid_at', [$startDate, $endDate])->where('status', 'success')->sum('amount'),
            'new_customers' => User::where('role', 'customer')->whereBetween('created_at', [$startDate, $endDate])->count(),
        ];

        // Daily revenue for chart
        $dailyData = [];
        $current = $startDate->copy();
        while ($current <= $endDate && count($dailyData) <= 31) {
            $dailyData[] = [
                'date' => $current->format('d M'),
                'revenue' => Transaction::whereDate('paid_at', $current)->where('status', 'success')->sum('amount'),
                'bookings' => Booking::whereDate('scheduled_at', $current)->count(),
            ];
            $current->addDay();
        }

        // Package breakdown
        $packageBreakdown = ServicePackage::withCount(['bookings' => function ($q) use ($startDate, $endDate) {
            $q->whereBetween('scheduled_at', [$startDate, $endDate]);
        }])
        ->withSum(['bookings as revenue' => function ($q) use ($startDate, $endDate) {
            $q->whereBetween('scheduled_at', [$startDate, $endDate])->where('payment_status', 'paid');
        }], 'total_price')
        ->orderByDesc('bookings_count')
        ->get();

        $transactions = Transaction::with(['booking.user', 'booking.servicePackage'])
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->where('status', 'success')
            ->latest()
            ->paginate(20);

        return view('admin.reports.index', compact(
            'summary', 'dailyData', 'packageBreakdown', 'transactions',
            'period', 'year', 'month', 'startDate', 'endDate'
        ));
    }
}
