@extends('layouts.app')

@section('title', 'Detail Pelanggan')
@section('page-title', 'Detail Pelanggan')
@section('page-subtitle', $user->name)

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-body text-center">
                <img src="{{ $user->avatar_url }}" class="rounded-circle mb-3" style="width:80px;height:80px;object-fit:cover;">
                <h6 class="fw-bold mb-0">{{ $user->name }}</h6>
                <div class="text-muted" style="font-size:0.85rem;">{{ $user->email }}</div>
                <div class="text-muted" style="font-size:0.85rem;">{{ $user->phone }}</div>
                <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-secondary' }} rounded-pill mt-2">
                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>

                <hr>

                <div class="row g-2 text-center">
                    <div class="col-4">
                        <div style="font-size:1.3rem;font-weight:700;font-family:'Space Grotesk',sans-serif;color:#0EA5E9;">{{ $stats['total_bookings'] }}</div>
                        <div style="font-size:0.7rem;color:#94A3B8;">Booking</div>
                    </div>
                    <div class="col-4">
                        <div style="font-size:1.3rem;font-weight:700;font-family:'Space Grotesk',sans-serif;color:#10B981;">{{ $stats['completed'] }}</div>
                        <div style="font-size:0.7rem;color:#94A3B8;">Selesai</div>
                    </div>
                    <div class="col-4">
                        <div style="font-size:0.95rem;font-weight:700;font-family:'Space Grotesk',sans-serif;color:#7C3AED;">Rp{{ number_format($stats['total_spent']/1000,0) }}k</div>
                        <div style="font-size:0.7rem;color:#94A3B8;">Total Bayar</div>
                    </div>
                </div>

                <hr>

                <form action="{{ route('admin.customers.toggle', $user) }}" method="POST" onsubmit="return confirm('Yakin?')">
                    @csrf
                    <button type="submit" class="btn btn-{{ $user->is_active ? 'outline-danger' : 'outline-success' }} btn-sm w-100">
                        <i class="bi bi-{{ $user->is_active ? 'lock' : 'unlock' }} me-1"></i>
                        {{ $user->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}
                    </button>
                </form>
            </div>
        </div>

        <!-- Vehicles -->
        <div class="card">
            <div class="card-header"><h6 class="mb-0 fw-bold"><i class="bi bi-bicycle me-2 text-primary"></i>Motor Terdaftar</h6></div>
            <div class="card-body">
                @forelse($user->vehicles as $v)
                <div class="d-flex align-items-center gap-2 mb-2 pb-2" style="border-bottom:1px solid #F1F5F9;">
                    <i class="bi {{ $v->type_icon }} text-primary"></i>
                    <div>
                        <div style="font-size:0.85rem;font-weight:600;">{{ $v->brand }} {{ $v->model }}</div>
                        <div style="font-size:0.75rem;color:#94A3B8;">{{ $v->license_plate }}</div>
                    </div>
                </div>
                @empty
                <p class="text-muted text-center mb-0" style="font-size:0.85rem;">Belum ada motor</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><h6 class="mb-0 fw-bold"><i class="bi bi-calendar-check me-2 text-primary"></i>Riwayat Booking</h6></div>
            <div class="card-body p-0">
                @if($user->bookings->isEmpty())
                    <div class="text-center py-5 text-muted" style="font-size:0.85rem;">Belum ada booking</div>
                @else
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead><tr><th>Kode</th><th>Motor</th><th>Paket</th><th>Tanggal</th><th>Total</th><th>Status</th></tr></thead>
                        <tbody>
                            @foreach($user->bookings->sortByDesc('created_at') as $b)
                            <tr>
                                <td style="font-family:monospace;font-size:0.78rem;">{{ $b->booking_code }}</td>
                                <td style="font-size:0.83rem;">{{ $b->vehicle->brand }} {{ $b->vehicle->model }}</td>
                                <td style="font-size:0.83rem;">{{ $b->servicePackage->name }}</td>
                                <td style="font-size:0.8rem;">{{ $b->scheduled_at->format('d/m/Y H:i') }}</td>
                                <td style="font-size:0.85rem;font-weight:600;">{{ $b->formatted_price }}</td>
                                <td>
                                    <span class="badge bg-{{ $b->status_color }}-subtle text-{{ $b->status_color }} rounded-pill" style="font-size:0.7rem;">
                                        {{ $b->status_label }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
