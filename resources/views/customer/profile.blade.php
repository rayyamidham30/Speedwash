@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')
@section('page-subtitle', 'Kelola informasi akun dan motor Anda')

@section('content')
<div class="row g-4">
    <!-- Left: Profile Info -->
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-body text-center">
                <img src="{{ $user->avatar_url }}" class="rounded-circle mb-3" style="width:90px;height:90px;object-fit:cover;border:3px solid #F0F9FF;">
                <h6 class="fw-bold mb-0">{{ $user->name }}</h6>
                <div class="text-muted" style="font-size:0.85rem;">{{ $user->email }}</div>
                <span class="badge bg-primary rounded-pill mt-2">
                    <i class="bi bi-person-check me-1"></i>Pelanggan
                </span>

                <hr>

                <form action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-2 text-start">
                        <label class="form-label">Foto Profil</label>
                        <input type="file" name="avatar" class="form-control form-control-sm @error('avatar') is-invalid @enderror" accept="image/*">
                        @error('avatar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-2 text-start">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control form-control-sm @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-2 text-start">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control form-control-sm" value="{{ $user->email }}" disabled>
                        <small class="text-muted" style="font-size:0.7rem;">Email tidak dapat diubah</small>
                    </div>
                    <div class="mb-2 text-start">
                        <label class="form-label">Nomor HP</label>
                        <input type="text" name="phone" class="form-control form-control-sm @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3 text-start">
                        <label class="form-label">Alamat</label>
                        <textarea name="address" class="form-control form-control-sm" rows="2">{{ old('address', $user->address) }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>

        <!-- Change Password -->
        <div class="card">
            <div class="card-header"><h6 class="mb-0 fw-bold"><i class="bi bi-shield-lock me-2 text-primary"></i>Ubah Password</h6></div>
            <div class="card-body">
                <form action="{{ route('customer.profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-2">
                        <label class="form-label">Password Saat Ini</label>
                        <input type="password" name="current_password" class="form-control form-control-sm @error('current_password') is-invalid @enderror">
                        @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password" class="form-control form-control-sm @error('password') is-invalid @enderror">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control form-control-sm">
                    </div>
                    <button type="submit" class="btn btn-outline-primary btn-sm w-100">
                        <i class="bi bi-key me-1"></i> Ubah Password
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Right: Vehicles -->
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-bold"><i class="bi bi-bicycle me-2 text-primary"></i>Motor Saya</h6>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addVehicleModal">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Motor
                </button>
            </div>
            <div class="card-body">
                @if($vehicles->isEmpty())
                    <div class="text-center py-4">
                        <i class="bi bi-bicycle" style="font-size:2.5rem;color:#CBD5E1;"></i>
                        <p class="mt-2 text-muted" style="font-size:0.85rem;">Belum ada motor terdaftar</p>
                    </div>
                @else
                <div class="row g-3">
                    @foreach($vehicles as $vehicle)
                    <div class="col-md-6">
                        <div class="card h-100" style="border:1.5px solid #E2E8F0;">
                            <div class="card-body">
                                <div class="d-flex align-items-start justify-content-between">
                                    <div class="d-flex align-items-center gap-3">
                                        <div style="width:48px;height:48px;background:#EFF6FF;border-radius:12px;display:flex;align-items:center;justify-content:center;">
                                            <i class="bi {{ $vehicle->type_icon }}" style="color:#0EA5E9;font-size:1.3rem;"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $vehicle->brand }} {{ $vehicle->model }}</div>
                                            <div style="font-size:0.85rem;color:#64748B;">{{ $vehicle->license_plate }}</div>
                                            <div style="font-size:0.78rem;color:#94A3B8;">{{ $vehicle->color }} &bull; {{ $vehicle->year }} &bull; {{ $vehicle->type_label }}</div>
                                        </div>
                                    </div>
                                    <form action="{{ route('customer.vehicles.destroy', $vehicle) }}" method="POST"
                                        onsubmit="return confirm('Hapus motor ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        <!-- Account Info Summary -->
        <div class="card">
            <div class="card-header"><h6 class="mb-0 fw-bold"><i class="bi bi-graph-up me-2 text-primary"></i>Ringkasan Akun</h6></div>
            <div class="card-body">
                <div class="row g-3 text-center">
                    <div class="col-4">
                        <div style="font-size:1.5rem;font-weight:700;font-family:'Space Grotesk',sans-serif;color:#0EA5E9;">{{ $user->bookings()->count() }}</div>
                        <div style="font-size:0.78rem;color:#64748B;">Total Booking</div>
                    </div>
                    <div class="col-4">
                        <div style="font-size:1.5rem;font-weight:700;font-family:'Space Grotesk',sans-serif;color:#10B981;">{{ $user->bookings()->where('status','completed')->count() }}</div>
                        <div style="font-size:0.78rem;color:#64748B;">Selesai</div>
                    </div>
                    <div class="col-4">
                        <div style="font-size:1.1rem;font-weight:700;font-family:'Space Grotesk',sans-serif;color:#7C3AED;">
                            Rp {{ number_format($user->bookings()->where('payment_status','paid')->sum('total_price'), 0, ',', '.') }}
                        </div>
                        <div style="font-size:0.78rem;color:#64748B;">Total Pengeluaran</div>
                    </div>
                </div>
                <hr>
                <div style="font-size:0.8rem;color:#94A3B8;">
                    <i class="bi bi-calendar-event me-2"></i>Bergabung sejak {{ $user->created_at->isoFormat('D MMMM Y') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Vehicle Modal -->
<div class="modal fade" id="addVehicleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:14px;">
            <form action="{{ route('customer.vehicles.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2 text-primary"></i>Tambah Motor</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Merek <span class="text-danger">*</span></label>
                            <input type="text" name="brand" class="form-control" placeholder="Honda, Yamaha, dll" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Model <span class="text-danger">*</span></label>
                            <input type="text" name="model" class="form-control" placeholder="Beat, NMAX, dll" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Plat Nomor <span class="text-danger">*</span></label>
                            <input type="text" name="license_plate" class="form-control" placeholder="N 1234 AB" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Warna</label>
                            <input type="text" name="color" class="form-control" placeholder="Hitam, Putih, dll">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tahun</label>
                            <input type="number" name="year" class="form-control" placeholder="2022" min="1990" max="{{ date('Y') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tipe <span class="text-danger">*</span></label>
                            <select name="type" class="form-select" required>
                                <option value="matic">Matic</option>
                                <option value="manual">Manual</option>
                                <option value="sport">Sport</option>
                                <option value="listrik">Listrik</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($errors->any() && session('open_modal'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new bootstrap.Modal(document.getElementById('addVehicleModal')).show();
    });
</script>
@endif
@endsection
