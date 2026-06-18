@extends('layouts.app')

@section('title', 'Dashboard Pelanggan')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Selamat datang, ' . auth()->user()->name)

@section('content')
<div class="row g-3 mb-4">
    <!-- Stats -->
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-calendar-check"></i></div>
            <div>
                <div class="stat-value">{{ $stats['total_bookings'] }}</div>
                <div class="stat-label">Total Booking</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-check2-circle"></i></div>
            <div>
                <div class="stat-value">{{ $stats['completed'] }}</div>
                <div class="stat-label">Selesai</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon yellow"><i class="bi bi-clock-history"></i></div>
            <div>
                <div class="stat-value">{{ $stats['active'] }}</div>
                <div class="stat-label">Aktif</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="bi bi-wallet2"></i></div>
            <div>
                <div class="stat-value" style="font-size:1rem;">Rp {{ number_format($stats['total_spent'], 0, ',', '.') }}</div>
                <div class="stat-label">Total Pengeluaran</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Active Booking -->
    <div class="col-12">
        @if($activeBooking)
        <div class="card border-0 mb-4" style="background: linear-gradient(135deg, #0F172A, #1E3A5F); color: white; border-radius: 16px;">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div style="width:48px; height:48px; background:rgba(14,165,233,0.2); border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.4rem;">
                                <i class="bi {{ $activeBooking->status_icon }}"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold" style="font-family:'Space Grotesk',sans-serif;">Booking Aktif</h6>
                                <div style="font-size:0.8rem; opacity:0.6;">{{ $activeBooking->booking_code }}</div>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-sm-4">
                                <div style="font-size:0.7rem; opacity:0.5; text-transform:uppercase; letter-spacing:0.05em;">Motor</div>
                                <div style="font-weight:600; font-size:0.9rem;">{{ $activeBooking->vehicle->brand }} {{ $activeBooking->vehicle->model }}</div>
                                <div style="font-size:0.8rem; opacity:0.7;">{{ $activeBooking->vehicle->license_plate }}</div>
                            </div>
                            <div class="col-sm-4">
                                <div style="font-size:0.7rem; opacity:0.5; text-transform:uppercase; letter-spacing:0.05em;">Paket</div>
                                <div style="font-weight:600; font-size:0.9rem;">{{ $activeBooking->servicePackage->name }}</div>
                                <div style="font-size:0.8rem; opacity:0.7;">{{ $activeBooking->servicePackage->formatted_price }}</div>
                            </div>
                            <div class="col-sm-4">
                                <div style="font-size:0.7rem; opacity:0.5; text-transform:uppercase; letter-spacing:0.05em;">No. Antrian</div>
                                <div style="font-weight:700; font-size:1.8rem; font-family:'Space Grotesk',sans-serif; color:#38BDF8;">
                                    #{{ $activeBooking->queue_number }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <div class="mb-2">
                            <span class="badge rounded-pill px-3 py-2"
                                style="background:rgba(14,165,233,0.25); color:#38BDF8; font-size:0.8rem;">
                                <i class="bi {{ $activeBooking->status_icon }} me-1"></i>
                                {{ $activeBooking->status_label }}
                            </span>
                        </div>
                        <a href="{{ route('customer.queue') }}" class="btn btn-sm"
                            style="background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.2); color:white; border-radius:8px;">
                            <i class="bi bi-eye me-1"></i> Lihat Antrian
                        </a>
                        <a href="{{ route('customer.bookings.show', $activeBooking) }}" class="btn btn-sm ms-2"
                            style="background:#0EA5E9; border:none; color:white; border-radius:8px;">
                            Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- CTA if no active booking -->
        <div class="card border-2 border-dashed mb-4" style="border-color:#CBD5E1 !important; background:#F8FAFC;">
            <div class="card-body text-center py-4">
                <div style="font-size:2.5rem; margin-bottom:0.75rem;">🏍️</div>
                <h6 class="fw-bold mb-1">Belum ada booking aktif</h6>
                <p class="text-muted mb-3" style="font-size:0.85rem;">Yuk booking cuci motor sekarang dan biarkan kami merawat motor Anda!</p>
                <a href="{{ route('customer.bookings.create') }}" class="btn btn-primary btn-sm px-4">
                    <i class="bi bi-plus-circle me-2"></i>Booking Sekarang
                </a>
            </div>
        </div>
        @endif
    </div>

    <!-- Queue Status -->
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-bold"><i class="bi bi-list-ol me-2 text-primary"></i>Status Antrian Hari Ini</h6>
                <span class="badge {{ $queueMonitor->is_open ? 'bg-success' : 'bg-danger' }} rounded-pill">
                    {{ $queueMonitor->is_open ? 'Buka' : 'Tutup' }}
                </span>
            </div>
            <div class="card-body text-center">
                <div style="font-size:0.75rem; color:#94A3B8; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.5rem;">
                    Sedang Dilayani
                </div>
                <div class="queue-number-display text-primary">#{{ $queueMonitor->current_serving }}</div>
                <div class="row g-2 mt-3">
                    <div class="col-6">
                        <div class="p-2 rounded-3" style="background:#F0F9FF;">
                            <div style="font-size:1.3rem; font-weight:700; color:#0284C7; font-family:'Space Grotesk',sans-serif;">{{ $queueMonitor->total_queue }}</div>
                            <div style="font-size:0.7rem; color:#64748B;">Total Antrian</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 rounded-3" style="background:#ECFDF5;">
                            <div style="font-size:1.3rem; font-weight:700; color:#059669; font-family:'Space Grotesk',sans-serif;">{{ $queueMonitor->available_slots - $queueMonitor->total_queue }}</div>
                            <div style="font-size:0.7rem; color:#64748B;">Slot Tersedia</div>
                        </div>
                    </div>
                </div>
                <a href="{{ route('customer.queue') }}" class="btn btn-outline-primary btn-sm w-100 mt-3">
                    Lihat Monitor Antrian
                </a>
            </div>
        </div>
    </div>

    <!-- Service Packages -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 fw-bold"><i class="bi bi-box-seam me-2 text-primary"></i>Paket Layanan</h6>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    @foreach($packages as $pkg)
                    <div class="col-sm-6">
                        <div class="package-card" onclick="window.location='{{ route('customer.bookings.create') }}'">
                            <div class="d-flex align-items-start gap-2">
                                <div style="width:36px; height:36px; background:{{ $pkg->color }}1A; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                    <i class="bi {{ $pkg->icon }}" style="color:{{ $pkg->color }};"></i>
                                </div>
                                <div>
                                    <div style="font-size:0.85rem; font-weight:700; color:#0F172A;">{{ $pkg->name }}</div>
                                    <div style="font-size:0.75rem; color:#94A3B8;">{{ $pkg->duration_text }}</div>
                                    <div style="font-size:0.9rem; font-weight:700; color:{{ $pkg->color }};">{{ $pkg->formatted_price }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-3">
                    <a href="{{ route('customer.bookings.create') }}" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-plus-circle me-2"></i>Booking Cuci Motor Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Bookings -->
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Booking Terbaru</h6>
                <a href="{{ route('customer.bookings.index') }}" class="btn btn-sm btn-outline-secondary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                @if($recentBookings->isEmpty())
                    <div class="text-center py-5">
                        <i class="bi bi-calendar-x" style="font-size:2rem; color:#CBD5E1;"></i>
                        <p class="mt-2 text-muted" style="font-size:0.85rem;">Belum ada riwayat booking</p>
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
                                <th>Harga</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentBookings as $booking)
                            <tr>
                                <td>
                                    <span class="fw-semibold" style="font-size:0.8rem; font-family:monospace;">
                                        {{ $booking->booking_code }}
                                    </span>
                                </td>
                                <td>
                                    <div style="font-size:0.85rem;">{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}</div>
                                    <div style="font-size:0.75rem; color:#94A3B8;">{{ $booking->vehicle->license_plate }}</div>
                                </td>
                                <td style="font-size:0.85rem;">{{ $booking->servicePackage->name }}</td>
                                <td style="font-size:0.82rem;">{{ $booking->scheduled_at->isoFormat('D MMM Y, HH:mm') }}</td>
                                <td style="font-size:0.85rem; font-weight:600;">{{ $booking->formatted_price }}</td>
                                <td>
                                    <span class="badge bg-{{ $booking->status_color }}-subtle text-{{ $booking->status_color }} rounded-pill">
                                        <i class="bi {{ $booking->status_icon }} me-1"></i>
                                        {{ $booking->status_label }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('customer.bookings.show', $booking) }}"
                                        class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye"></i>
                                    </a>
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
