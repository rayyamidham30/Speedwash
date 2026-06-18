@extends('layouts.app')

@section('title', 'Tambah Paket')
@section('page-title', 'Tambah Paket Layanan')
@section('page-subtitle', 'Buat paket layanan baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.packages.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Paket <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kode Paket <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" placeholder="Contoh: VIP_WASH" value="{{ old('code') }}" required>
                            @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" class="form-control" rows="2">{{ old('description') }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" min="0" required>
                            @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Durasi (menit) <span class="text-danger">*</span></label>
                            <input type="number" name="duration_minutes" class="form-control @error('duration_minutes') is-invalid @enderror" value="{{ old('duration_minutes', 30) }}" min="5" required>
                            @error('duration_minutes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Urutan</label>
                            <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}" min="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Warna Badge</label>
                            <input type="color" name="color" class="form-control form-control-color" value="{{ old('color', '#3B82F6') }}" style="width:100%; height:42px;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Icon (Bootstrap Icons class)</label>
                            <input type="text" name="icon" class="form-control" placeholder="bi-droplet-fill" value="{{ old('icon', 'bi-droplet-fill') }}">
                            <small class="text-muted" style="font-size:0.75rem;">Lihat referensi di <a href="https://icons.getbootstrap.com" target="_blank">icons.getbootstrap.com</a></small>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Fitur Layanan</label>
                            <div id="featuresContainer">
                                <div class="input-group mb-2">
                                    <input type="text" name="features[]" class="form-control" placeholder="Contoh: Cuci body motor">
                                    <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()"><i class="bi bi-trash"></i></button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="addFeature()">
                                <i class="bi bi-plus me-1"></i> Tambah Fitur
                            </button>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <a href="{{ route('admin.packages.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan Paket</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function addFeature() {
    const container = document.getElementById('featuresContainer');
    const div = document.createElement('div');
    div.className = 'input-group mb-2';
    div.innerHTML = `<input type="text" name="features[]" class="form-control" placeholder="Fitur layanan">
        <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()"><i class="bi bi-trash"></i></button>`;
    container.appendChild(div);
}
</script>
@endpush
