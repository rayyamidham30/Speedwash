@extends('layouts.app')

@section('title', 'Monitor Antrian')
@section('page-title', 'Monitor Antrian')
@section('page-subtitle', 'Pantau posisi antrian Anda secara real-time')

@push('styles')
<style>
.queue-board {
    background: linear-gradient(135deg, #0F172A, #1E3A5F);
    border-radius: 20px;
    padding: 2rem;
    color: white;
    text-align: center;
}
.current-number {
    font-size: 7rem;
    font-weight: 800;
    font-family: 'Space Grotesk', sans-serif;
    color: #38BDF8;
    line-height: 1;
    text-shadow: 0 0 40px rgba(56,189,248,0.4);
    animation: pulse-glow 2s ease-in-out infinite;
}
@keyframes pulse-glow {
    0%, 100% { text-shadow: 0 0 20px rgba(56,189,248,0.3); }
    50% { text-shadow: 0 0 50px rgba(56,189,248,0.7); }
}
.my-queue-card {
    background: linear-gradient(135deg, #0EA5E9, #38BDF8);
    border-radius: 16px;
    padding: 1.5rem;
    color: white;
}
.queue-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem 1rem;
    border-radius: 10px;
    transition: all 0.2s;
}
.queue-item:hover { background: #F8FAFC; }
.queue-item.active {
    background: linear-gradient(135deg, #ECFDF5, #D1FAE5);
    border: 1px solid #6EE7B7;
}
.queue-item.in-progress {
    background: linear-gradient(135deg, #EFF6FF, #DBEAFE);
    border: 1px solid #93C5FD;
}
</style>
@endpush

@section('content')
<div class="row g-4">
    <!-- Queue Board -->
    <div class="col-lg-5">
        <div class="queue-board mb-4">
            <div style="font-size:0.75rem;opacity:0.5;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.5rem;">
                Sedang Dilayani
            </div>
            <div class="current-number">#{{ $queueMonitor->current_serving }}</div>
            <div class="mt-3" style="opacity:0.7;font-size:0.9rem;">
                <i class="bi bi-clock me-1"></i>
                {{ now()->isoFormat('HH:mm:ss') }} WIB
            </div>

            <div class="row g-3 mt-3">
                <div class="col-4">
                    <div style="background:rgba(255,255,255,0.08);border-radius:12px;padding:0.75rem;">
                        <div style="font-size:1.5rem;font-weight:700;font-family:'Space Grotesk',sans-serif;color:#38BDF8;">
                            {{ $queueMonitor->total_queue }}
                        </div>
                        <div style="font-size:0.7rem;opacity:0.5;">Total</div>
                    </div>
                </div>
                <div class="col-4">
                    <div style="background:rgba(255,255,255,0.08);border-radius:12px;padding:0.75rem;">
                        <div style="font-size:1.5rem;font-weight:700;font-family:'Space Grotesk',sans-serif;color:#34D399;">
                            {{ $queueMonitor->remaining_wait }}
                        </div>
                        <div style="font-size:0.7rem;opacity:0.5;">Menunggu</div>
                    </div>
                </div>
                <div class="col-4">
                    <div style="background:rgba(255,255,255,0.08);border-radius:12px;padding:0.75rem;">
                        <div style="font-size:1.5rem;font-weight:700;font-family:'Space Grotesk',sans-serif;color:#FBBF24;">
                            ~{{ $queueMonitor->estimated_wait_minutes }}m
                        </div>
                        <div style="font-size:0.7rem;opacity:0.5;">Est. Tunggu</div>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <span class="badge px-3 py-2 rounded-pill {{ $queueMonitor->is_open ? '' : '' }}"
                    style="background:{{ $queueMonitor->is_open ? 'rgba(52,211,153,0.2)' : 'rgba(239,68,68,0.2)' }};color:{{ $queueMonitor->is_open ? '#34D399' : '#F87171' }};font-size:0.8rem;">
                    <i class="bi bi-circle-fill me-1" style="font-size:0.5rem;"></i>
                    {{ $queueMonitor->is_open ? 'Buka — ' . $queueMonitor->open_time . ' s/d ' . $queueMonitor->close_time : 'Tutup' }}
                </span>
            </div>
        </div>

        <!-- My Active Booking -->
        @if($myActiveBooking)
        <div class="my-queue-card">
            <div style="font-size:0.7rem;opacity:0.7;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.5rem;">
                Antrian Saya
            </div>
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div style="font-size:2.5rem;font-weight:800;font-family:'Space Grotesk',sans-serif;line-height:1;">
                        #{{ $myActiveBooking->queue_number }}
                    </div>
                    <div style="font-size:0.85rem;opacity:0.85;margin-top:0.25rem;">
                        {{ $myActiveBooking->vehicle->brand }} {{ $myActiveBooking->vehicle->model }}
                    </div>
                    <div style="font-size:0.8rem;opacity:0.7;">
                        {{ $myActiveBooking->servicePackage->name }}
                    </div>
                </div>
                <div class="text-end">
                    <span class="badge px-2 py-1 rounded-pill mb-2 d-block" style="background:rgba(255,255,255,0.2);font-size:0.75rem;">
                        {{ $myActiveBooking->status_label }}
                    </span>
                    @php $ahead = max(0, $myActiveBooking->queue_number - $queueMonitor->current_serving); @endphp
                    <div style="font-size:0.75rem;opacity:0.75;">
                        @if($myActiveBooking->status === 'in_progress')
                            <i class="bi bi-droplet-fill me-1"></i>Sedang diproses
                        @elseif($ahead === 0)
                            Giliran Anda!
                        @else
                            {{ $ahead }} antrian lagi
                        @endif
                    </div>
                </div>
            </div>
            <a href="{{ route('customer.bookings.show', $myActiveBooking) }}" class="btn btn-sm w-100 mt-3"
                style="background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.3);color:white;border-radius:8px;">
                <i class="bi bi-arrow-right me-1"></i> Lihat Detail Booking
            </a>
        </div>
        @else
        <div class="card">
            <div class="card-body text-center py-4">
                <i class="bi bi-calendar-x" style="font-size:2rem;color:#CBD5E1;"></i>
                <p class="mt-2 mb-3 text-muted" style="font-size:0.85rem;">Tidak ada booking aktif hari ini</p>
                <a href="{{ route('customer.bookings.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle me-1"></i> Booking Sekarang
                </a>
            </div>
        </div>
        @endif
    </div>

    <!-- Queue List -->
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-bold"><i class="bi bi-list-ol me-2 text-primary"></i>Daftar Antrian Hari Ini</h6>
                <span class="badge bg-primary rounded-pill">{{ $currentQueue->count() }}</span>
            </div>
            <div class="card-body p-0">
                @if($currentQueue->isEmpty())
                    <div class="text-center py-5">
                        <i class="bi bi-inbox" style="font-size:2.5rem;color:#CBD5E1;"></i>
                        <p class="mt-2 text-muted" style="font-size:0.85rem;">Belum ada antrian hari ini</p>
                    </div>
                @else
                <div class="p-3">
                    @foreach($currentQueue as $item)
                    <div class="queue-item {{ $item->status === 'in_progress' ? 'in-progress' : ($item->queue_number === $queueMonitor->current_serving ? 'active' : '') }} mb-2
                        {{ $myActiveBooking && $myActiveBooking->id === $item->id ? 'border border-primary' : '' }}">
                        <div style="width:40px;height:40px;border-radius:12px;background:{{ $item->status === 'in_progress' ? '#EFF6FF' : ($item->queue_number <= $queueMonitor->current_serving ? '#ECFDF5' : '#F8FAFC') }};display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1rem;font-family:'Space Grotesk',sans-serif;color:{{ $item->status === 'in_progress' ? '#0284C7' : ($item->queue_number <= $queueMonitor->current_serving ? '#059669' : '#94A3B8') }};flex-shrink:0;">
                            {{ $item->queue_number }}
                        </div>
                        <div class="flex-grow-1 min-width-0">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-1">
                                <div>
                                    <span class="fw-semibold" style="font-size:0.875rem;">
                                        {{ $myActiveBooking && $myActiveBooking->id === $item->id ? '⭐ ' : '' }}
                                        {{ $item->vehicle->brand }} {{ $item->vehicle->model }}
                                    </span>
                                    <span style="font-size:0.75rem;color:#94A3B8;margin-left:0.5rem;">{{ $item->vehicle->license_plate }}</span>
                                </div>
                                <span class="badge rounded-pill bg-{{ $item->status_color }}-subtle text-{{ $item->status_color }}" style="font-size:0.7rem;">
                                    <i class="bi {{ $item->status_icon }} me-1"></i>{{ $item->status_label }}
                                </span>
                            </div>
                            <div style="font-size:0.78rem;color:#64748B;margin-top:0.15rem;">
                                <i class="bi bi-box-seam me-1"></i>{{ $item->servicePackage->name }}
                                <span class="mx-2">&bull;</span>
                                <i class="bi bi-clock me-1"></i>{{ $item->servicePackage->duration_text }}
                            </div>
                        </div>
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
    // Auto-refresh every 30 seconds
    setInterval(() => location.reload(), 30000);

    // Live clock update
    setInterval(() => {
        const t = new Date().toLocaleTimeString('id-ID', {hour:'2-digit',minute:'2-digit',second:'2-digit'});
        document.querySelectorAll('.live-time').forEach(el => el.textContent = t);
    }, 1000);
</script>
@endpush
