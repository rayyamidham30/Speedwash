@extends('layouts.app')

@section('title', 'Riwayat Booking')
@section('page-title', 'Riwayat Booking')
@section('page-subtitle', 'Semua riwayat booking cuci motor Anda')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div></div>
    <a href="{{ route('customer.bookings.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Booking Baru
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        @if($bookings->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-calendar-x" style="font-size:3rem;color:#CBD5E1;"></i>
                <h6 class="mt-3 fw-bold">Belum ada booking</h6>
                <p class="text-muted" style="font-size:0.85rem;">Yuk mulai booking cuci motor pertama Anda!</p>
                <a href="{{ route('customer.bookings.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle me-2"></i>Booking Sekarang
                </a>
            </div>
        @else
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Kode Booking</th>
                        <th>Motor</th>
                        <th>Paket</th>
                        <th>Jadwal</th>
                        <th>No. Antrian</th>
                        <th>Total</th>
                        <th>Pembayaran</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                    <tr>
                        <td>
                            <span class="fw-semibold" style="font-family:monospace;font-size:0.8rem;">{{ $booking->booking_code }}</span>
                        </td>
                        <td>
                            <div style="font-size:0.85rem;font-weight:600;">{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}</div>
                            <div style="font-size:0.75rem;color:#94A3B8;">{{ $booking->vehicle->license_plate }}</div>
                        </td>
                        <td>
                            <span style="font-size:0.85rem;">{{ $booking->servicePackage->name }}</span>
                        </td>
                        <td style="font-size:0.82rem;">
                            <div>{{ $booking->scheduled_at->isoFormat('D MMM Y') }}</div>
                            <div style="color:#94A3B8;">{{ $booking->scheduled_at->format('H:i') }} WIB</div>
                        </td>
                        <td>
                            @if($booking->queue_number)
                                <span class="badge bg-primary rounded-pill px-2">#{{ $booking->queue_number }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td style="font-size:0.85rem;font-weight:600;">{{ $booking->formatted_price }}</td>
                        <td>
                            <span class="badge rounded-pill {{ $booking->payment_status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ $booking->payment_status_label }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $booking->status_color }}-subtle text-{{ $booking->status_color }} rounded-pill" style="font-size:0.72rem;">
                                <i class="bi {{ $booking->status_icon }} me-1"></i>{{ $booking->status_label }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('customer.bookings.show', $booking) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if(in_array($booking->status, ['pending','confirmed']))
                                <form action="{{ route('customer.bookings.cancel', $booking) }}" method="POST"
                                    onsubmit="return confirm('Batalkan booking ini?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Batalkan">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3">
            {{ $bookings->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
