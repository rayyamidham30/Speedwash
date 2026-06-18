<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\QueueMonitor;
use App\Models\ServicePackage;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'total_bookings' => Booking::where('user_id', $user->id)->count(),
            'completed' => Booking::where('user_id', $user->id)->where('status', 'completed')->count(),
            'active' => Booking::where('user_id', $user->id)->whereIn('status', ['pending', 'confirmed', 'in_queue', 'in_progress'])->count(),
            'total_spent' => Booking::where('user_id', $user->id)->where('payment_status', 'paid')->sum('total_price'),
        ];

        $recentBookings = Booking::where('user_id', $user->id)
            ->with(['vehicle', 'servicePackage'])
            ->latest()
            ->limit(5)
            ->get();

        $activeBooking = Booking::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'confirmed', 'in_queue', 'in_progress'])
            ->with(['vehicle', 'servicePackage'])
            ->latest()
            ->first();

        $queueMonitor = QueueMonitor::today();
        $packages = ServicePackage::active()->limit(4)->get();

        return view('customer.dashboard', compact('stats', 'recentBookings', 'activeBooking', 'queueMonitor', 'packages'));
    }
}
