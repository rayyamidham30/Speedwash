@extends('layouts.app')

@section('title', 'Laporan')
@section('page-title', 'Laporan')
@section('page-subtitle', 'Laporan transaksi dan kinerja layanan')

@section('content')
<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Periode</label>
                <select name="period" class="form-select" onchange="this.form.submit()">
                    <option value="weekly" {{ $period=='weekly'?'selected':'' }}>Minggu Ini</option>
                    <option value="monthly" {{ $period=='monthly'?'selected':'' }}>Bulanan</option>
                    <option value="yearly" {{ $period=='yearly'?'selected':'' }}>Tahunan</option>
                </select>
            </div>
            @if($period === 'monthly')
            <div class="col-md-2">
                <label class="form-label">Bulan</label>
                <select name="month" class="form-select" onchange="this.form.submit()">
                    @foreach(range(1,12) as $m)
                        <option value="{{ $m }}" {{ $month==$m?'selected':'' }}>{{ \Carbon\Carbon::create()->month($m)->isoFormat('MMMM') }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            @if($period !== 'weekly')
            <div class="col-md-2">
                <label class="form-label">Tahun</label>
                <select name="year" class="form-select" onchange="this.form.submit()">
                    @foreach(range(date('Y'), date('Y')-3) as $y)
                        <option value="{{ $y }}" {{ $year==$y?'selected':'' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="col-md-3 ms-auto text-md-end">
                <span class="text-muted" style="font-size:0.85rem;">
                    Periode: {{ $startDate->isoFormat('D MMM Y') }} - {{ $endDate->isoFormat('D MMM Y') }}
                </span>
            </div>
        </form>
    </div>
</div>

<!-- Summary -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-calendar-check"></i></div>
            <div>
                <div class="stat-value">{{ $summary['total_bookings'] }}</div>
                <div class="stat-label">Total Booking</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-check2-circle"></i></div>
            <div>
                <div class="stat-value">{{ $summary['completed_bookings'] }}</div>
                <div class="stat-label">Selesai</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon red"><i class="bi bi-x-circle"></i></div>
            <div>
                <div class="stat-value">{{ $summary['cancelled_bookings'] }}</div>
                <div class="stat-label">Dibatalkan</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="bi bi-cash-stack"></i></div>
            <div>
                <div class="stat-value" style="font-size:1.05rem;">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</div>
                <div class="stat-label">Total Pendapatan</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Chart -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><h6 class="mb-0 fw-bold"><i class="bi bi-graph-up me-2 text-primary"></i>Grafik Pendapatan & Booking</h6></div>
            <div class="card-body">
                <canvas id="reportChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Package Breakdown -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header"><h6 class="mb-0 fw-bold"><i class="bi bi-pie-chart me-2 text-primary"></i>Per Paket Layanan</h6></div>
            <div class="card-body">
                @foreach($packageBreakdown as $pkg)
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2" style="border-bottom:1px solid #F1F5F9;">
                    <div>
                        <div style="font-size:0.85rem;font-weight:600;">
                            <i class="bi {{ $pkg->icon }} me-1" style="color:{{ $pkg->color }};"></i>{{ $pkg->name }}
                        </div>
                        <div style="font-size:0.75rem;color:#94A3B8;">{{ $pkg->bookings_count }} booking</div>
                    </div>
                    <span class="fw-bold" style="font-size:0.85rem; color:{{ $pkg->color }};">
                        Rp {{ number_format($pkg->revenue ?? 0, 0, ',', '.') }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Transactions Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold"><i class="bi bi-receipt me-2 text-primary"></i>Riwayat Transaksi</h6>
        <button class="btn btn-sm btn-outline-secondary" onclick="window.print()"><i class="bi bi-printer me-1"></i> Cetak</button>
    </div>
    <div class="card-body p-0">
        @if($transactions->isEmpty())
            <div class="text-center py-5 text-muted" style="font-size:0.85rem;">Tidak ada transaksi pada periode ini</div>
        @else
        <div class="table-responsive">
            <table class="table mb-0">
                <thead><tr><th>Kode Transaksi</th><th>Pelanggan</th><th>Paket</th><th>Metode</th><th>Tanggal</th><th>Jumlah</th></tr></thead>
                <tbody>
                    @foreach($transactions as $trx)
                    <tr>
                        <td style="font-family:monospace;font-size:0.78rem;">{{ $trx->transaction_code }}</td>
                        <td style="font-size:0.85rem;">{{ $trx->booking->user->name }}</td>
                        <td style="font-size:0.83rem;">{{ $trx->booking->servicePackage->name }}</td>
                        <td><span class="badge bg-light text-dark text-capitalize">{{ $trx->payment_method }}</span></td>
                        <td style="font-size:0.8rem;">{{ $trx->paid_at?->isoFormat('D MMM Y, HH:mm') }}</td>
                        <td class="fw-bold text-success">{{ $trx->formatted_amount }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $transactions->withQueryString()->links() }}</div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
const ctx = document.getElementById('reportChart');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode(array_column($dailyData, 'date')) !!},
        datasets: [
            {
                label: 'Pendapatan (Rp)',
                data: {!! json_encode(array_column($dailyData, 'revenue')) !!},
                borderColor: '#0EA5E9',
                backgroundColor: 'rgba(14,165,233,0.1)',
                fill: true,
                tension: 0.3,
                yAxisID: 'y',
            },
            {
                label: 'Jumlah Booking',
                data: {!! json_encode(array_column($dailyData, 'bookings')) !!},
                borderColor: '#10B981',
                backgroundColor: 'rgba(16,185,129,0.1)',
                fill: true,
                tension: 0.3,
                yAxisID: 'y1',
            }
        ]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        scales: {
            y: { type: 'linear', position: 'left', beginAtZero: true, grid: { color: '#F1F5F9' } },
            y1: { type: 'linear', position: 'right', beginAtZero: true, grid: { drawOnChartArea: false } },
            x: { grid: { display: false } }
        }
    }
});
</script>
@endpush
