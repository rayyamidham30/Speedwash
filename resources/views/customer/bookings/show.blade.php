@extends('layouts.app')

@section('title', 'Detail Booking')
@section('page-title', 'Detail Booking')
@section('page-subtitle', $booking->booking_code)

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <!-- Status Card -->
        <div class="card mb-4 border-0" style="background:linear-gradient(135deg,#0F172A,#1E3A5F); color:white; border-radius:16px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:52px;height:52px;background:rgba(14,165,233,0.2);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.6rem;">
                            <i class="bi {{ $booking->status_icon }}"></i>
                        </div>
                        <div>
                            <div style="font-size:0.75rem;opacity:0.5;text-transform:uppercase;letter-spacing:0.08em;">Status Booking</div>
                            <div style="font-size:1.2rem;font-weight:700;font-family:'Space Grotesk',sans-serif;">{{ $booking->status_label }}</div>
                            <div style="font-size:0.8rem;opacity:0.6;">{{ $booking->booking_code }}</div>
                        </div>
                    </div>
                    @if($booking->queue_number)
                    <div class="text-center">
                        <div style="font-size:0.7rem;opacity:0.5;text-transform:uppercase;letter-spacing:0.08em;">No. Antrian</div>
                        <div style="font-size:3rem;font-weight:800;font-family:'Space Grotesk',sans-serif;color:#38BDF8;line-height:1;">#{{ $booking->queue_number }}</div>
                    </div>
                    @endif
                </div>

                <!-- Progress Steps -->
                @php
                    $steps = [
                        'pending' => 0,
                        'confirmed' => 1,
                        'in_queue' => 2,
                        'in_progress' => 3,
                        'completed' => 4,
                    ];
                    $currentStep = $steps[$booking->status] ?? 0;
                @endphp
                @if($booking->status !== 'cancelled')
                <div class="step-indicator mt-4">
                    @foreach(['Menunggu','Dikonfirmasi','Antrian','Proses','Selesai'] as $i => $label)
                    <div class="step {{ $currentStep > $i ? 'done' : ($currentStep == $i ? 'active' : '') }}">
                        <div class="step-icon" style="background:{{ $currentStep > $i ? '#10B981' : ($currentStep == $i ? '#0EA5E9' : 'rgba(255,255,255,0.1)') }};border-color:{{ $currentStep > $i ? '#10B981' : ($currentStep == $i ? '#0EA5E9' : 'rgba(255,255,255,0.2)') }};color:{{ $currentStep >= $i ? 'white' : 'rgba(255,255,255,0.4)' }};">
                            @if($currentStep > $i)
                                <i class="bi bi-check"></i>
                            @else
                                {{ $i + 1 }}
                            @endif
                        </div>
                        <div style="font-size:0.65rem;margin-top:0.4rem;color:{{ $currentStep >= $i ? 'rgba(255,255,255,0.9)' : 'rgba(255,255,255,0.35)' }};white-space:nowrap;">{{ $label }}</div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        <!-- Booking Details -->
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0 fw-bold"><i class="bi bi-info-circle me-2 text-primary"></i>Detail Booking</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div style="font-size:0.75rem;color:#94A3B8;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.25rem;">Motor</div>
                        <div class="fw-semibold">{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}</div>
                        <div style="font-size:0.85rem;color:#64748B;">{{ $booking->vehicle->license_plate }} &bull; {{ $booking->vehicle->color }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div style="font-size:0.75rem;color:#94A3B8;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.25rem;">Paket Layanan</div>
                        <div class="fw-semibold">{{ $booking->servicePackage->name }}</div>
                        <div style="font-size:0.85rem;color:#64748B;">⏱ {{ $booking->servicePackage->duration_text }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div style="font-size:0.75rem;color:#94A3B8;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.25rem;">Jadwal</div>
                        <div class="fw-semibold">{{ $booking->scheduled_at->isoFormat('dddd, D MMMM Y') }}</div>
                        <div style="font-size:0.85rem;color:#64748B;">Pukul {{ $booking->scheduled_at->format('H:i') }} WIB</div>
                    </div>
                    <div class="col-sm-6">
                        <div style="font-size:0.75rem;color:#94A3B8;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.25rem;">Pembayaran</div>
                        <div class="fw-semibold">{{ $booking->formatted_price }}</div>
                        <span class="badge rounded-pill {{ $booking->payment_status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                            {{ $booking->payment_status_label }}
                        </span>
                    </div>
                    @if($booking->started_at)
                    <div class="col-sm-6">
                        <div style="font-size:0.75rem;color:#94A3B8;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.25rem;">Mulai Proses</div>
                        <div class="fw-semibold">{{ $booking->started_at->isoFormat('D MMM Y, HH:mm') }}</div>
                    </div>
                    @endif
                    @if($booking->completed_at)
                    <div class="col-sm-6">
                        <div style="font-size:0.75rem;color:#94A3B8;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.25rem;">Selesai</div>
                        <div class="fw-semibold text-success">{{ $booking->completed_at->isoFormat('D MMM Y, HH:mm') }}</div>
                    </div>
                    @endif
                    @if($booking->notes)
                    <div class="col-12">
                        <div style="font-size:0.75rem;color:#94A3B8;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.25rem;">Catatan</div>
                        <div style="background:#F8FAFC;border-radius:8px;padding:0.75rem;font-size:0.85rem;">{{ $booking->notes }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Features -->
        @if($booking->servicePackage->features)
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0 fw-bold"><i class="bi bi-list-check me-2 text-primary"></i>Layanan Termasuk</h6></div>
            <div class="card-body">
                <div class="row g-2">
                    @foreach($booking->servicePackage->features as $feature)
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill text-success" style="font-size:0.85rem;"></i>
                            <span style="font-size:0.875rem;">{{ $feature }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Actions -->
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('customer.bookings.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
            @if(in_array($booking->status, ['pending', 'confirmed']))
            <form action="{{ route('customer.bookings.cancel', $booking) }}" method="POST"
                onsubmit="return confirm('Yakin ingin membatalkan booking ini?')">
                @csrf
                <button type="submit" class="btn btn-outline-danger">
                    <i class="bi bi-x-circle me-1"></i> Batalkan Booking
                </button>
            </form>
            @endif
            <a href="{{ route('customer.queue') }}" class="btn btn-outline-primary">
                <i class="bi bi-list-ol me-1"></i> Monitor Antrian
            </a>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Transaction Info -->
        @if($booking->transaction)
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0 fw-bold"><i class="bi bi-receipt me-2 text-success"></i>Info Transaksi</h6></div>
            <div class="card-body">
                <div class="mb-2">
                    <div style="font-size:0.75rem;color:#94A3B8;">Kode Transaksi</div>
                    <div class="fw-bold" style="font-family:monospace;font-size:0.85rem;">{{ $booking->transaction->transaction_code }}</div>
                </div>
                <div class="mb-2">
                    <div style="font-size:0.75rem;color:#94A3B8;">Metode Bayar</div>
                    <div class="fw-semibold text-capitalize">{{ $booking->transaction->payment_method }}</div>
                </div>
                <div class="mb-2">
                    <div style="font-size:0.75rem;color:#94A3B8;">Waktu Bayar</div>
                    <div class="fw-semibold">{{ $booking->transaction->paid_at?->isoFormat('D MMM Y, HH:mm') }}</div>
                </div>
                <hr>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Total Bayar</span>
                    <span class="fw-bold text-success" style="font-size:1.1rem;">{{ $booking->transaction->formatted_amount }}</span>
                </div>
            </div>
        </div>
        @endif

        <!-- Quick Info -->
        <div class="card">
            <div class="card-header"><h6 class="mb-0 fw-bold"><i class="bi bi-lightbulb me-2 text-warning"></i>Info</h6></div>
            <div class="card-body">
                <ul class="list-unstyled mb-0" style="font-size:0.82rem;color:#64748B;">
                    <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Datang sesuai jadwal booking</li>
                    <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Tunjukkan kode booking ke petugas</li>
                    <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Pembayaran dilakukan setelah selesai</li>
                    <li><i class="bi bi-info-circle text-primary me-2"></i>Antrian dapat dilihat secara real-time</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
