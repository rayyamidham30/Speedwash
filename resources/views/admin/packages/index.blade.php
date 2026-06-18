@extends('layouts.app')

@section('title', 'Paket Layanan')
@section('page-title', 'Paket Layanan')
@section('page-subtitle', 'Kelola paket layanan cuci motor')

@section('content')
<div class="d-flex justify-content-end mb-4">
    <a href="{{ route('admin.packages.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Tambah Paket
    </a>
</div>

<div class="row g-4">
    @foreach($packages as $pkg)
    <div class="col-md-6 col-xl-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div style="width:48px;height:48px;background:{{ $pkg->color }}1A;border-radius:12px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi {{ $pkg->icon }}" style="color:{{ $pkg->color }};font-size:1.4rem;"></i>
                    </div>
                    <span class="badge {{ $pkg->is_active ? 'bg-success' : 'bg-secondary' }} rounded-pill">
                        {{ $pkg->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
                <h6 class="fw-bold mb-1">{{ $pkg->name }}</h6>
                <p class="text-muted mb-2" style="font-size:0.8rem; min-height:2.5rem;">{{ $pkg->description }}</p>
                <div class="fw-bold mb-1" style="font-size:1.3rem; color:{{ $pkg->color }}; font-family:'Space Grotesk',sans-serif;">
                    {{ $pkg->formatted_price }}
                </div>
                <div style="font-size:0.78rem;color:#94A3B8;margin-bottom:0.75rem;">
                    <i class="bi bi-clock me-1"></i>{{ $pkg->duration_text }}
                    <span class="mx-1">&bull;</span>
                    <i class="bi bi-calendar-check me-1"></i>{{ $pkg->bookings_count }} booking
                </div>

                @if($pkg->features)
                <div class="mb-3">
                    @foreach(array_slice($pkg->features, 0, 3) as $f)
                        <div style="font-size:0.75rem;color:#64748B;"><i class="bi bi-check-circle text-success me-1"></i>{{ $f }}</div>
                    @endforeach
                </div>
                @endif

                <div class="d-flex gap-2">
                    <a href="{{ route('admin.packages.edit', $pkg) }}" class="btn btn-sm btn-outline-primary flex-grow-1">
                        <i class="bi bi-pencil me-1"></i> Edit
                    </a>
                    <form action="{{ route('admin.packages.destroy', $pkg) }}" method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus paket ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
