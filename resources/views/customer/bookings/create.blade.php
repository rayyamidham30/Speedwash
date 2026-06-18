@extends('layouts.app')

@section('title', 'Booking Cuci Motor')
@section('page-title', 'Booking Cuci Motor')
@section('page-subtitle', 'Isi form di bawah untuk melakukan booking')

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <form action="{{ route('customer.bookings.store') }}" method="POST" id="bookingForm">
            @csrf

            <!-- Step 1: Choose Package -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold">
                        <span class="badge bg-primary me-2 rounded-circle" style="width:24px;height:24px;line-height:24px;padding:0;text-align:center;">1</span>
                        Pilih Paket Layanan
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3" id="packageList">
                        @foreach($packages as $pkg)
                        <div class="col-sm-6">
                            <div class="package-card {{ old('service_package_id') == $pkg->id ? 'selected' : '' }}"
                                onclick="selectPackage({{ $pkg->id }}, '{{ $pkg->name }}', '{{ $pkg->price }}', '{{ $pkg->duration_text }}')"
                                data-package-id="{{ $pkg->id }}">
                                <div class="d-flex align-items-start gap-3">
                                    <div style="width:44px; height:44px; background:{{ $pkg->color }}1A; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.2rem; flex-shrink:0;">
                                        <i class="bi {{ $pkg->icon }}" style="color:{{ $pkg->color }};"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold mb-1" style="font-size:0.9rem; color:#0F172A;">{{ $pkg->name }}</div>
                                        <div class="text-muted mb-2" style="font-size:0.78rem;">{{ $pkg->description }}</div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span style="font-weight:700; color:{{ $pkg->color }}; font-size:1rem;">{{ $pkg->formatted_price }}</span>
                                            <span class="badge rounded-pill" style="background:{{ $pkg->color }}1A; color:{{ $pkg->color }};">
                                                <i class="bi bi-clock me-1"></i>{{ $pkg->duration_text }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                @if($pkg->features)
                                <div class="mt-2 pt-2" style="border-top:1px solid #F1F5F9;">
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach(array_slice($pkg->features, 0, 3) as $feature)
                                            <span style="font-size:0.7rem; background:#F8FAFC; border:1px solid #E2E8F0; padding:0.15rem 0.5rem; border-radius:20px; color:#64748B;">
                                                ✓ {{ $feature }}
                                            </span>
                                        @endforeach
                                        @if(count($pkg->features) > 3)
                                            <span style="font-size:0.7rem; color:#94A3B8;">+{{ count($pkg->features) - 3 }} lainnya</span>
                                        @endif
                                    </div>
                                </div>
                                @endif

                                <input type="radio" name="service_package_id" value="{{ $pkg->id }}"
                                    class="d-none package-radio" id="pkg_{{ $pkg->id }}"
                                    {{ old('service_package_id') == $pkg->id ? 'checked' : '' }}>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @error('service_package_id')
                        <div class="text-danger mt-2" style="font-size:0.8rem;"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Step 2: Choose Vehicle -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold">
                        <span class="badge bg-primary me-2 rounded-circle" style="width:24px;height:24px;line-height:24px;padding:0;text-align:center;">2</span>
                        Pilih Motor
                    </h6>
                </div>
                <div class="card-body">
                    @if($vehicles->isEmpty())
                        <div class="alert" style="background:#FFFBEB; color:#92400E; border:1px solid #FDE68A;">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Anda belum menambahkan motor. <a href="{{ route('customer.profile') }}" class="fw-bold">Tambah motor di profil</a> terlebih dahulu.
                        </div>
                    @else
                        <div class="row g-2">
                            @foreach($vehicles as $vehicle)
                            <div class="col-sm-6">
                                <div class="package-card {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}"
                                    onclick="selectVehicle({{ $vehicle->id }})"
                                    data-vehicle-id="{{ $vehicle->id }}">
                                    <div class="d-flex align-items-center gap-3">
                                        <div style="width:40px; height:40px; background:#EFF6FF; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                                            <i class="bi {{ $vehicle->type_icon }}" style="color:#0EA5E9; font-size:1.1rem;"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold" style="font-size:0.9rem;">{{ $vehicle->brand }} {{ $vehicle->model }}</div>
                                            <div style="font-size:0.8rem; color:#64748B;">
                                                <span class="me-2">{{ $vehicle->license_plate }}</span>
                                                <span class="badge" style="background:#F1F5F9; color:#64748B; font-size:0.65rem;">{{ $vehicle->type_label }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="radio" name="vehicle_id" value="{{ $vehicle->id }}"
                                        class="d-none vehicle-radio"
                                        {{ old('vehicle_id') == $vehicle->id ? 'checked' : '' }}>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @error('vehicle_id')
                            <div class="text-danger mt-2" style="font-size:0.8rem;"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    @endif
                </div>
            </div>

            <!-- Step 3: Schedule -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold">
                        <span class="badge bg-primary me-2 rounded-circle" style="width:24px;height:24px;line-height:24px;padding:0;text-align:center;">3</span>
                        Jadwal & Catatan
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tanggal & Waktu Booking <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="scheduled_at" id="scheduled_at"
                                class="form-control @error('scheduled_at') is-invalid @enderror"
                                value="{{ old('scheduled_at', now()->addHour()->format('Y-m-d\TH:i')) }}"
                                min="{{ now()->format('Y-m-d\TH:i') }}">
                            @error('scheduled_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Slot Tersedia Hari Ini</label>
                            <div class="form-control" style="background:#F8FAFC; display:flex; align-items:center; gap:0.5rem;">
                                @php $remaining = $queue->available_slots - $queue->total_queue; @endphp
                                @if($remaining > 5)
                                    <i class="bi bi-check-circle-fill text-success"></i>
                                    <span class="text-success fw-semibold">{{ $remaining }} slot tersedia</span>
                                @elseif($remaining > 0)
                                    <i class="bi bi-exclamation-circle-fill text-warning"></i>
                                    <span class="text-warning fw-semibold">Hampir penuh! {{ $remaining }} slot tersisa</span>
                                @else
                                    <i class="bi bi-x-circle-fill text-danger"></i>
                                    <span class="text-danger fw-semibold">Slot hari ini penuh</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Catatan Tambahan</label>
                            <textarea name="notes" class="form-control" rows="3"
                                placeholder="Contoh: Motor kotor karena hujan, ada noda membandel di bagian...">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn" {{ $vehicles->isEmpty() ? 'disabled' : '' }}>
                    <i class="bi bi-calendar-plus me-2"></i>Konfirmasi Booking
                </button>
            </div>
        </form>
    </div>

    <!-- Order Summary Sidebar -->
    <div class="col-lg-4">
        <div class="card" style="position:sticky; top:80px;">
            <div class="card-header">
                <h6 class="mb-0 fw-bold"><i class="bi bi-receipt me-2 text-primary"></i>Ringkasan Pesanan</h6>
            </div>
            <div class="card-body">
                <div id="summary-empty" class="text-center py-3">
                    <i class="bi bi-clipboard-x" style="font-size:2rem; color:#CBD5E1;"></i>
                    <p class="text-muted mt-2 mb-0" style="font-size:0.85rem;">Pilih paket untuk melihat ringkasan</p>
                </div>

                <div id="summary-content" class="d-none">
                    <div class="mb-3">
                        <div style="font-size:0.75rem; color:#94A3B8; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.25rem;">Paket Dipilih</div>
                        <div id="summary-package" class="fw-bold"></div>
                        <div id="summary-duration" class="text-muted" style="font-size:0.8rem;"></div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span style="font-size:0.85rem;">Harga Layanan</span>
                        <span id="summary-price" class="fw-bold"></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span style="font-size:0.85rem;">Biaya Admin</span>
                        <span class="text-success fw-semibold">Gratis</span>
                    </div>

                    <div class="p-3 rounded-3" style="background:#F0F9FF; border:1px solid #BAE6FD;">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Total</span>
                            <span id="summary-total" class="fw-bold" style="font-size:1.2rem; color:#0284C7; font-family:'Space Grotesk',sans-serif;"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Queue Info -->
            <div class="card-footer" style="background:#F8FAFC;">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-info-circle text-primary"></i>
                    <span class="fw-semibold" style="font-size:0.8rem;">Info Antrian Saat Ini</span>
                </div>
                <div class="row g-2 text-center">
                    <div class="col-6">
                        <div style="font-size:1.4rem; font-weight:700; color:#0EA5E9; font-family:'Space Grotesk',sans-serif;">{{ $queue->current_serving }}</div>
                        <div style="font-size:0.7rem; color:#64748B;">Sedang Dilayani</div>
                    </div>
                    <div class="col-6">
                        <div style="font-size:1.4rem; font-weight:700; color:#0F172A; font-family:'Space Grotesk',sans-serif;">{{ $queue->total_queue }}</div>
                        <div style="font-size:0.7rem; color:#64748B;">Total Antrian</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function selectPackage(id, name, price, duration) {
    // Update selection
    document.querySelectorAll('.package-card[data-package-id]').forEach(c => c.classList.remove('selected'));
    document.querySelector(`[data-package-id="${id}"]`).classList.add('selected');
    document.getElementById(`pkg_${id}`).checked = true;

    // Update summary
    const formatted = 'Rp ' + parseInt(price).toLocaleString('id-ID');
    document.getElementById('summary-empty').classList.add('d-none');
    document.getElementById('summary-content').classList.remove('d-none');
    document.getElementById('summary-package').textContent = name;
    document.getElementById('summary-duration').textContent = '⏱ Estimasi ' + duration;
    document.getElementById('summary-price').textContent = formatted;
    document.getElementById('summary-total').textContent = formatted;
}

function selectVehicle(id) {
    document.querySelectorAll('.package-card[data-vehicle-id]').forEach(c => c.classList.remove('selected'));
    document.querySelector(`[data-vehicle-id="${id}"]`).classList.add('selected');
    document.querySelector(`[data-vehicle-id="${id}"] input[type="radio"]`).checked = true;
}

// Initialize if old values exist
@if(old('service_package_id'))
document.querySelector(`[data-package-id="{{ old('service_package_id') }}"]`)?.classList.add('selected');
@endif
@if(old('vehicle_id'))
document.querySelector(`[data-vehicle-id="{{ old('vehicle_id') }}"]`)?.classList.add('selected');
@endif
</script>
@endpush
