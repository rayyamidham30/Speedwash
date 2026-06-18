@extends('layouts.app')

@section('title', 'Monitor Antrian')
@section('page-title', 'Monitor Antrian')
@section('page-subtitle', 'Kelola antrian cuci motor secara real-time')

@section('content')
<div class="row g-4">
    <!-- Control Panel -->
    <div class="col-lg-4">
        <div class="card mb-4" style="background:linear-gradient(135deg,#0F172A,#1E3A5F); color:white;">
            <div class="card-body text-center">
                <div style="font-size:0.75rem;opacity:0.5;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.5rem;">
                    Sedang Dilayani
                </div>
                <div style="font-size:5rem;font-weight:800;font-family:'Space Grotesk',sans-serif;color:#38BDF8;line-height:1;">
                    #{{ $queueMonitor->current_serving }}
                </div>
                <div class="row g-2 mt-3">
                    <div class="col-6">
                        <div style="background:rgba(255,255,255,0.08);border-radius:10px;padding:0.6rem;">
                            <div style="font-weight:700;font-size:1.3rem;font-family:'Space Grotesk',sans-serif;">{{ $queueMonitor->total_queue }}</div>
                            <div style="font-size:0.65rem;opacity:0.5;">Total Antrian</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div style="background:rgba(255,255,255,0.08);border-radius:10px;padding:0.6rem;">
                            <div style="font-weight:700;font-size:1.3rem;font-family:'Space Grotesk',sans-serif;color:#34D399;">{{ $queueMonitor->available_slots }}</div>
                            <div style="font-size:0.65rem;opacity:0.5;">Slot Tersedia</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h6 class="mb-0 fw-bold"><i class="bi bi-sliders me-2 text-primary"></i>Kontrol Antrian</h6></div>
            <div class="card-body">
                <form action="{{ route('admin.queue.update') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nomor Sedang Dilayani</label>
                        <div class="input-group">
                            <button type="button" class="btn btn-outline-secondary" onclick="adjustNumber(-1)">
                                <i class="bi bi-dash"></i>
                            </button>
                            <input type="number" name="current_serving" id="currentServing" class="form-control text-center fw-bold"
                                value="{{ $queueMonitor->current_serving }}" min="0">
                            <button type="button" class="btn btn-outline-secondary" onclick="adjustNumber(1)">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_open" id="is_open" {{ $queueMonitor->is_open ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="is_open">
                            Layanan {{ $queueMonitor->is_open ? 'Buka' : 'Tutup' }}
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check2-circle me-1"></i> Update Status Antrian
                    </button>
                </form>

                <div class="mt-3 p-2 rounded-3" style="background:#F8FAFC; font-size:0.78rem; color:#64748B;">
                    <i class="bi bi-info-circle me-1"></i>
                    Jam operasional: {{ $queueMonitor->open_time }} - {{ $queueMonitor->close_time }}
                </div>
            </div>
        </div>
    </div>

    <!-- Queue List -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h6 class="mb-0 fw-bold"><i class="bi bi-list-ol me-2 text-primary"></i>Antrian Tanggal {{ \Carbon\Carbon::parse($date)->isoFormat('D MMMM Y') }}</h6>
                <form method="GET" class="d-flex gap-2">
                    <input type="date" name="date" class="form-control form-control-sm" value="{{ $date }}" onchange="this.form.submit()">
                </form>
            </div>
            <div class="card-body p-0">
                @if($bookings->isEmpty())
                    <div class="text-center py-5">
                        <i class="bi bi-inbox" style="font-size:2.5rem;color:#CBD5E1;"></i>
                        <p class="mt-2 text-muted" style="font-size:0.85rem;">Tidak ada antrian pada tanggal ini</p>
                    </div>
                @else
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr><th>No.</th><th>Pelanggan</th><th>Motor</th><th>Paket</th><th>Jadwal</th><th>Status</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $b)
                            <tr class="{{ $b->queue_number == $queueMonitor->current_serving ? 'table-info' : '' }}">
                                <td>
                                    <span class="badge {{ $b->queue_number == $queueMonitor->current_serving ? 'bg-primary' : 'bg-secondary' }} rounded-pill" style="font-size:0.85rem;">
                                        {{ $b->queue_number }}
                                    </span>
                                </td>
                                <td style="font-size:0.85rem;font-weight:600;">{{ $b->user->name }}</td>
                                <td>
                                    <div style="font-size:0.83rem;">{{ $b->vehicle->brand }} {{ $b->vehicle->model }}</div>
                                    <div style="font-size:0.75rem;color:#94A3B8;">{{ $b->vehicle->license_plate }}</div>
                                </td>
                                <td style="font-size:0.83rem;">{{ $b->servicePackage->name }}</td>
                                <td style="font-size:0.8rem;">{{ $b->scheduled_at->format('H:i') }}</td>
                                <td>
                                    <span class="badge bg-{{ $b->status_color }}-subtle text-{{ $b->status_color }} rounded-pill" style="font-size:0.7rem;">
                                        {{ $b->status_label }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.bookings.show', $b) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-arrow-right"></i>
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

@push('scripts')
<script>
function adjustNumber(delta) {
    const input = document.getElementById('currentServing');
    let val = parseInt(input.value) + delta;
    if (val < 0) val = 0;
    input.value = val;
}
// Auto refresh
setTimeout(() => location.reload(), 30000);
</script>
@endpush
