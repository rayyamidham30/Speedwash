@extends('layouts.app')

@section('title', 'Kelola Booking')
@section('page-title', 'Kelola Booking')
@section('page-subtitle', 'Daftar semua booking pelanggan')

@section('content')
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Cari</label>
                <input type="text" name="search" class="form-control" placeholder="Kode booking / nama pelanggan" value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    @foreach(['pending'=>'Menunggu','confirmed'=>'Dikonfirmasi','in_queue'=>'Dalam Antrian','in_progress'=>'Sedang Dicuci','completed'=>'Selesai','cancelled'=>'Dibatalkan'] as $key => $label)
                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal</label>
                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search me-1"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        @if($bookings->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size:2.5rem;color:#CBD5E1;"></i>
                <p class="mt-2 text-muted">Tidak ada data booking</p>
            </div>
        @else
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr><th>Kode</th><th>Pelanggan</th><th>Motor</th><th>Paket</th><th>Jadwal</th><th>Total</th><th>Pembayaran</th><th>Status</th><th></th></tr>
                </thead>
                <tbody>
                    @foreach($bookings as $b)
                    <tr>
                        <td><span style="font-family:monospace;font-size:0.78rem;">{{ $b->booking_code }}</span></td>
                        <td>
                            <div style="font-size:0.85rem;font-weight:600;">{{ $b->user->name }}</div>
                            <div style="font-size:0.75rem;color:#94A3B8;">{{ $b->user->phone }}</div>
                        </td>
                        <td>
                            <div style="font-size:0.83rem;">{{ $b->vehicle->brand }} {{ $b->vehicle->model }}</div>
                            <div style="font-size:0.75rem;color:#94A3B8;">{{ $b->vehicle->license_plate }}</div>
                        </td>
                        <td style="font-size:0.83rem;">{{ $b->servicePackage->name }}</td>
                        <td style="font-size:0.8rem;">
                            <div>{{ $b->scheduled_at->format('d/m/Y') }}</div>
                            <div style="color:#94A3B8;">{{ $b->scheduled_at->format('H:i') }}</div>
                        </td>
                        <td style="font-size:0.85rem;font-weight:600;">{{ $b->formatted_price }}</td>
                        <td>
                            <span class="badge rounded-pill {{ $b->payment_status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }}" style="font-size:0.7rem;">
                                {{ $b->payment_status_label }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $b->status_color }}-subtle text-{{ $b->status_color }} rounded-pill" style="font-size:0.7rem;">
                                {{ $b->status_label }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.bookings.show', $b) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $bookings->withQueryString()->links() }}</div>
        @endif
    </div>
</div>
@endsection
