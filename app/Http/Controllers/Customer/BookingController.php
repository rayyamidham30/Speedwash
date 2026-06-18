<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ServicePackage;
use App\Models\Vehicle;
use App\Models\QueueMonitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->with(['vehicle', 'servicePackage'])
            ->latest()
            ->paginate(10);

        return view('customer.bookings.index', compact('bookings'));
    }

    public function create()
    {
        $vehicles = Vehicle::where('user_id', Auth::id())
            ->where('is_active', true)
            ->get();
        $packages = ServicePackage::active()->get();
        $queue = QueueMonitor::today();

        return view('customer.bookings.create', compact('vehicles', 'packages', 'queue'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'service_package_id' => ['required', 'exists:service_packages,id'],
            'scheduled_at' => ['required', 'date', 'after:now'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [
            'vehicle_id.required' => 'Pilih motor Anda.',
            'service_package_id.required' => 'Pilih paket layanan.',
            'scheduled_at.required' => 'Pilih waktu booking.',
            'scheduled_at.after' => 'Waktu booking harus di masa mendatang.',
        ]);

        // Verify vehicle belongs to user
        $vehicle = Vehicle::where('id', $validated['vehicle_id'])
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $package = ServicePackage::findOrFail($validated['service_package_id']);
        $queue = QueueMonitor::today();

        // Check if slots available
        if ($queue->total_queue >= $queue->available_slots) {
            return back()->withErrors(['scheduled_at' => 'Maaf, slot untuk hari ini sudah penuh. Pilih tanggal lain.']);
        }

        $queueNumber = $queue->total_queue + 1;

        $booking = Booking::create([
            'booking_code' => Booking::generateCode(),
            'user_id' => Auth::id(),
            'vehicle_id' => $vehicle->id,
            'service_package_id' => $package->id,
            'scheduled_at' => $validated['scheduled_at'],
            'status' => 'pending',
            'queue_number' => $queueNumber,
            'notes' => $validated['notes'],
            'total_price' => $package->price,
            'payment_status' => 'unpaid',
        ]);

        // Update queue monitor for today's bookings
        if (now()->toDateString() === date('Y-m-d', strtotime($validated['scheduled_at']))) {
            $queue->increment('total_queue');
        }

        return redirect()->route('customer.bookings.show', $booking)
            ->with('success', "Booking berhasil! Kode booking Anda: {$booking->booking_code}");
    }

    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);
        $booking->load(['vehicle', 'servicePackage', 'transaction']);
        return view('customer.bookings.show', compact('booking'));
    }

    public function cancel(Booking $booking)
    {
        $this->authorize('cancel', $booking);

        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return back()->withErrors(['status' => 'Booking tidak bisa dibatalkan pada status saat ini.']);
        }

        $booking->update([
            'status' => 'cancelled',
        ]);

        return redirect()->route('customer.bookings.index')
            ->with('success', 'Booking berhasil dibatalkan.');
    }

    public function queue()
    {
        $queueMonitor = QueueMonitor::today();
        $myActiveBooking = Booking::where('user_id', Auth::id())
            ->whereDate('scheduled_at', today())
            ->whereIn('status', ['confirmed', 'in_queue', 'in_progress'])
            ->with(['vehicle', 'servicePackage'])
            ->first();

        $currentQueue = Booking::whereDate('scheduled_at', today())
            ->whereIn('status', ['in_queue', 'in_progress', 'confirmed'])
            ->with(['user', 'vehicle', 'servicePackage'])
            ->orderBy('queue_number')
            ->get();

        return view('customer.queue', compact('queueMonitor', 'myActiveBooking', 'currentQueue'));
    }
}
