@extends('layouts.app')

@section('title', 'Detail Booking')
@section('page-title', 'Detail Booking')
@section('page-subtitle', $booking->booking_code)

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <!-- Status Header -->
        <div class="card mb-4 border-0" style="background:linear-gradient(135deg,#0F172A,#1E3A5F); color:white; border-radius:16px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:52px;height:52px;background:rgba(14,165,233,0.2);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.6rem;">
                            <i class="bi {{ $booking->status_icon }}"></i>
                        </div>
                        <div>
                            <div style="font-size:0.75rem;opacity:0.5;text-transform:uppercase;letter-spacing:0.08em;">Status</div>
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
            </div>
        </div>

        <!-- Customer & Vehicle Info -->
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0 fw-bold"><i class="bi bi-person me-2 text-primary"></i>Informasi Pelanggan</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div style="font-size:0.75rem;color:#94A3B8;text-transform:uppercase;">Nama</div>
                        <div class="fw-semibold">{{ $booking->user->name }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div style="font-size:0.75rem;color:#94A3B8;text-transform:uppercase;">Telepon</div>
                        <div class="fw-semibold">{{ $booking->user->phone }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div style="font-size:0.75rem;color:#94A3B8;text-transform:uppercase;">Email</div>
                        <div class="fw-semibold">{{ $booking->user->email }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div style="font-size:0.75rem;color:#94A3B8;text-transform:uppercase;">Motor</div>
                        <div class="fw-semibold">{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }} ({{ $booking->vehicle->license_plate }})</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Details -->
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0 fw-bold"><i class="bi bi-info-circle me-2 text-primary"></i>Detail Layanan</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div style="font-size:0.75rem;color:#94A3B8;text-transform:uppercase;">Paket</div>
                        <div class="fw-semibold">{{ $booking->servicePackage->name }}</div>
                        <div style="font-size:0.85rem;color:#64748B;">⏱ {{ $booking->servicePackage->duration_text }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div style="font-size:0.75rem;color:#94A3B8;text-transform:uppercase;">Harga</div>
                        <div class="fw-bold" style="font-size:1.1rem;color:#0EA5E9;">{{ $booking->formatted_price }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div style="font-size:0.75rem;color:#94A3B8;text-transform:uppercase;">Jadwal</div>
                        <div class="fw-semibold">{{ $booking->scheduled_at->isoFormat('dddd, D MMM Y, HH:mm') }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div style="font-size:0.75rem;color:#94A3B8;text-transform:uppercase;">Status Pembayaran</div>
                        <span class="badge rounded-pill {{ $booking->payment_status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                            {{ $booking->payment_status_label }}
                        </span>
                    </div>
                    @if($booking->notes)
                    <div class="col-12">
                        <div style="font-size:0.75rem;color:#94A3B8;text-transform:uppercase;">Catatan Pelanggan</div>
                        <div style="background:#F8FAFC;border-radius:8px;padding:0.75rem;font-size:0.85rem;">{{ $booking->notes }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Action Sidebar -->
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0 fw-bold"><i class="bi bi-gear me-2 text-primary"></i>Update Status</h6></div>
            <div class="card-body">
                <form action="{{ route('admin.bookings.status', $booking) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Status Booking</label>
                        <select name="status" class="form-select">
                            @foreach(['pending'=>'Menunggu Konfirmasi','confirmed'=>'Dikonfirmasi','in_queue'=>'Dalam Antrian','in_progress'=>'Sedang Dicuci','completed'=>'Selesai','cancelled'=>'Dibatalkan'] as $key => $label)
                                <option value="{{ $key }}" {{ $booking->status === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if($booking->payment_status !== 'paid')
                    <div class="mb-3">
                        <label class="form-label">Metode Pembayaran (jika selesai)</label>
                        <select name="payment_method" class="form-select">
                            <option value="cash">Tunai</option>
                            <option value="transfer">Transfer Bank</option>
                            <option value="qris">QRIS</option>
                        </select>
                    </div>
                    @endif

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check2-circle me-1"></i> Update Status
                    </button>
                </form>

                <!-- Quick Actions -->
                <div class="mt-3 d-grid gap-2">
                    @if($booking->status === 'pending')
                    <form action="{{ route('admin.bookings.status', $booking) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="confirmed">
                        <button type="submit" class="btn btn-outline-success btn-sm w-100">
                            <i class="bi bi-check-circle me-1"></i> Konfirmasi Booking
                        </button>
                    </form>
                    @endif
                    @if($booking->status === 'confirmed' || $booking->status === 'in_queue')
                    <form action="{{ route('admin.bookings.status', $booking) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="in_progress">
                        <button type="submit" class="btn btn-outline-primary btn-sm w-100">
                            <i class="bi bi-droplet-fill me-1"></i> Mulai Pengerjaan
                        </button>
                    </form>
                    @endif
                    @if($booking->status === 'in_progress')
                    <form action="{{ route('admin.bookings.status', $booking) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="completed">
                        <input type="hidden" name="payment_method" value="cash">
                        <button type="submit" class="btn btn-success btn-sm w-100">
                            <i class="bi bi-check2-circle me-1"></i> Selesaikan & Bayar Tunai
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>

        @if($booking->transaction)
        <div class="card">
            <div class="card-header"><h6 class="mb-0 fw-bold"><i class="bi bi-receipt me-2 text-success"></i>Transaksi</h6></div>
            <div class="card-body">
                <div class="mb-2">
                    <div style="font-size:0.75rem;color:#94A3B8;">Kode Transaksi</div>
                    <div class="fw-bold" style="font-family:monospace;">{{ $booking->transaction->transaction_code }}</div>
                </div>
                <div class="mb-2">
                    <div style="font-size:0.75rem;color:#94A3B8;">Metode</div>
                    <div class="fw-semibold text-capitalize">{{ $booking->transaction->payment_method }}</div>
                </div>
                <div class="d-flex justify-content-between mt-3 pt-2" style="border-top:1px solid #E2E8F0;">
                    <span class="fw-bold">Jumlah</span>
                    <span class="fw-bold text-success">{{ $booking->transaction->formatted_amount }}</span>
                </div>
            </div>
        </div>
        @endif

        <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary w-100 mt-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>
@endsection
