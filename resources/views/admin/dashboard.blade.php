@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')
@section('page-subtitle', 'Ringkasan operasional SPEEDWASH')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-people"></i></div>
            <div>
                <div class="stat-value">{{ $stats['total_customers'] }}</div>
                <div class="stat-label">Total Pelanggan</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-calendar-check"></i></div>
            <div>
                <div class="stat-value">{{ $stats['today_bookings'] }}</div>
                <div class="stat-label">Booking Hari Ini</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon yellow"><i class="bi bi-droplet-fill"></i></div>
            <div>
                <div class="stat-value">{{ $stats['in_progress'] }}</div>
                <div class="stat-label">Sedang Dicuci</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="bi bi-cash-coin"></i></div>
            <div>
                <div class="stat-value" style="font-size:1.05rem;">Rp {{ number_format($stats['today_revenue'], 0, ',', '.') }}</div>
                <div class="stat-label">Pendapatan Hari Ini</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Revenue Chart -->
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-bold"><i class="bi bi-graph-up me-2 text-primary"></i>Pendapatan 7 Hari Terakhir</h6>
                <span class="badge bg-success-subtle text-success rounded-pill">
                    Bulan ini: Rp {{ number_format($stats['monthly_revenue'], 0, ',', '.') }}
                </span>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="90"></canvas>
            </div>
        </div>
    </div>

    <!-- Queue Summary -->
    <div class="col-lg-4">
        <div class="card h-100" style="background:linear-gradient(135deg,#0F172A,#1E3A5F); color:white;">
            <div class="card-body">
                <h6 class="mb-3 fw-bold"><i class="bi bi-list-ol me-2"></i>Status Antrian</h6>
                <div class="text-center mb-3">
                    <div style="font-size:0.7rem;opacity:0.5;text-transform:uppercase;letter-spacing:0.1em;">Sedang Dilayani</div>
                    <div style="font-size:3.5rem;font-weight:800;font-family:'Space Grotesk',sans-serif;color:#38BDF8;line-height:1;">
                        #{{ $queueMonitor->current_serving }}
                    </div>
                </div>
                <div class="row g-2 text-center mb-3">
                    <div class="col-4">
                        <div style="background:rgba(255,255,255,0.08);border-radius:10px;padding:0.5rem;">
                            <div style="font-weight:700;font-size:1.2rem;font-family:'Space Grotesk',sans-serif;">{{ $stats['pending_today'] }}</div>
                            <div style="font-size:0.65rem;opacity:0.5;">Menunggu</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div style="background:rgba(255,255,255,0.08);border-radius:10px;padding:0.5rem;">
                            <div style="font-weight:700;font-size:1.2rem;font-family:'Space Grotesk',sans-serif;color:#38BDF8;">{{ $stats['in_progress'] }}</div>
                            <div style="font-size:0.65rem;opacity:0.5;">Proses</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div style="background:rgba(255,255,255,0.08);border-radius:10px;padding:0.5rem;">
                            <div style="font-weight:700;font-size:1.2rem;font-family:'Space Grotesk',sans-serif;color:#34D399;">{{ $stats['completed_today'] }}</div>
                            <div style="font-size:0.65rem;opacity:0.5;">Selesai</div>
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.queue') }}" class="btn btn-sm w-100" style="background:#0EA5E9;border:none;color:white;">
                    <i class="bi bi-arrow-right me-1"></i> Kelola Antrian
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Today's Bookings -->
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-bold"><i class="bi bi-calendar-day me-2 text-primary"></i>Booking Hari Ini</h6>
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-outline-secondary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                @if($todayBookings->isEmpty())
                    <div class="text-center py-5">
                        <i class="bi bi-calendar-x" style="font-size:2rem;color:#CBD5E1;"></i>
                        <p class="mt-2 text-muted" style="font-size:0.85rem;">Belum ada booking hari ini</p>
                    </div>
                @else
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead><tr><th>#</th><th>Pelanggan</th><th>Paket</th><th>Waktu</th><th>Status</th></tr></thead>
                        <tbody>
                            @foreach($todayBookings->take(8) as $b)
                            <tr>
                                <td><span class="badge bg-primary rounded-pill">{{ $b->queue_number }}</span></td>
                                <td>
                                    <div style="font-size:0.85rem;font-weight:600;">{{ $b->user->name }}</div>
                                    <div style="font-size:0.75rem;color:#94A3B8;">{{ $b->vehicle->brand }} {{ $b->vehicle->model }} - {{ $b->vehicle->license_plate }}</div>
                                </td>
                                <td style="font-size:0.85rem;">{{ $b->servicePackage->name }}</td>
                                <td style="font-size:0.82rem;">{{ $b->scheduled_at->format('H:i') }}</td>
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

    <!-- Package Popularity -->
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header"><h6 class="mb-0 fw-bold"><i class="bi bi-pie-chart me-2 text-primary"></i>Popularitas Paket (Bulan Ini)</h6></div>
            <div class="card-body">
                @foreach($packageStats as $pkg)
                @php
                    $max = $packageStats->max('bookings_count') ?: 1;
                    $percent = $max > 0 ? ($pkg->bookings_count / $max) * 100 : 0;
                @endphp
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span style="font-size:0.85rem;font-weight:600;">
                            <i class="bi {{ $pkg->icon }} me-1" style="color:{{ $pkg->color }};"></i>{{ $pkg->name }}
                        </span>
                        <span style="font-size:0.85rem;font-weight:700;color:{{ $pkg->color }};">{{ $pkg->bookings_count }}</span>
                    </div>
                    <div class="progress" style="height:6px;border-radius:10px;">
                        <div class="progress-bar" style="width:{{ $percent }}%; background:{{ $pkg->color }}; border-radius:10px;"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="card mt-4">
            <div class="card-header"><h6 class="mb-0 fw-bold"><i class="bi bi-receipt me-2 text-success"></i>Transaksi Terbaru</h6></div>
            <div class="card-body p-0">
                @if($recentTransactions->isEmpty())
                    <div class="text-center py-4 text-muted" style="font-size:0.85rem;">Belum ada transaksi</div>
                @else
                <div style="max-height:280px; overflow-y:auto;">
                    @foreach($recentTransactions as $trx)
                    <div class="d-flex justify-content-between align-items-center px-3 py-2" style="border-bottom:1px solid #F1F5F9;">
                        <div>
                            <div style="font-size:0.82rem;font-weight:600;">{{ $trx->booking->user->name }}</div>
                            <div style="font-size:0.72rem;color:#94A3B8;">{{ $trx->booking->servicePackage->name }} &bull; {{ $trx->paid_at?->diffForHumans() }}</div>
                        </div>
                        <span class="fw-bold text-success" style="font-size:0.85rem;">{{ $trx->formatted_amount }}</span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const ctx = document.getElementById('revenueChart');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode(array_column($revenueChart, 'date')) !!},
        datasets: [{
            label: 'Pendapatan (Rp)',
            data: {!! json_encode(array_column($revenueChart, 'revenue')) !!},
            backgroundColor: '#0EA5E9',
            borderRadius: 6,
            barThickness: 28,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: '#F1F5F9' },
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + (value/1000) + 'k';
                    }
                }
            },
            x: { grid: { display: false } }
        }
    }
});
</script>
@endpush
