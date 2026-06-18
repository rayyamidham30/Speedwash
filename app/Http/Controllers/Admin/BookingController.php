<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Transaction;
use App\Models\QueueMonitor;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'vehicle', 'servicePackage'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date')) {
            $query->whereDate('scheduled_at', $request->date);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('booking_code', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', '%' . $request->search . '%'));
            });
        }

        $bookings = $query->paginate(15);
        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'vehicle', 'servicePackage', 'transaction']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => ['required', 'in:pending,confirmed,in_queue,in_progress,completed,cancelled'],
        ]);

        $oldStatus = $booking->status;
        $newStatus = $request->status;

        $updateData = ['status' => $newStatus];

        if ($newStatus === 'in_progress' && $oldStatus !== 'in_progress') {
            $updateData['started_at'] = now();
        }

        if ($newStatus === 'completed' && $oldStatus !== 'completed') {
            $updateData['completed_at'] = now();
            $updateData['payment_status'] = 'paid';

            // Create transaction
            Transaction::create([
                'transaction_code' => Transaction::generateCode(),
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'amount' => $booking->total_price,
                'payment_method' => $request->payment_method ?? 'cash',
                'status' => 'success',
                'paid_at' => now(),
            ]);

            // Update queue monitor
            $queue = QueueMonitor::today();
            $queue->increment('current_serving');
        }

        $booking->update($updateData);

        return back()->with('success', 'Status booking berhasil diperbarui.');
    }

    public function queue(Request $request)
    {
        $queueMonitor = QueueMonitor::today();
        $date = $request->date ?? today()->toDateString();

        $bookings = Booking::whereDate('scheduled_at', $date)
            ->whereIn('status', ['confirmed', 'in_queue', 'in_progress', 'completed'])
            ->with(['user', 'vehicle', 'servicePackage'])
            ->orderBy('queue_number')
            ->get();

        return view('admin.queue', compact('queueMonitor', 'bookings', 'date'));
    }

    public function updateQueue(Request $request)
    {
        $queue = QueueMonitor::today();
        $queue->update([
            'current_serving' => $request->current_serving ?? $queue->current_serving,
            'is_open' => $request->has('is_open'),
        ]);

        return back()->with('success', 'Status antrian diperbarui.');
    }
}
