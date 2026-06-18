@extends('layouts.app')

@section('title', 'Kelola Pelanggan')
@section('page-title', 'Kelola Pelanggan')
@section('page-subtitle', 'Daftar semua pelanggan terdaftar')

@section('content')
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-6">
                <label class="form-label">Cari</label>
                <input type="text" name="search" class="form-control" placeholder="Nama, email, atau nomor HP" value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    <option value="active" {{ request('status')=='active'?'selected':'' }}>Aktif</option>
                    <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Nonaktif</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search me-1"></i> Cari</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr><th>Pelanggan</th><th>Kontak</th><th>Total Booking</th><th>Bergabung</th><th>Status</th><th></th></tr>
                </thead>
                <tbody>
                    @foreach($customers as $c)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $c->avatar_url }}" class="rounded-circle" style="width:36px;height:36px;object-fit:cover;">
                                <span class="fw-semibold" style="font-size:0.85rem;">{{ $c->name }}</span>
                            </div>
                        </td>
                        <td>
                            <div style="font-size:0.82rem;">{{ $c->email }}</div>
                            <div style="font-size:0.78rem;color:#94A3B8;">{{ $c->phone }}</div>
                        </td>
                        <td><span class="badge bg-primary-subtle text-primary rounded-pill">{{ $c->bookings_count }}</span></td>
                        <td style="font-size:0.82rem;">{{ $c->created_at->isoFormat('D MMM Y') }}</td>
                        <td>
                            <span class="badge {{ $c->is_active ? 'bg-success' : 'bg-secondary' }} rounded-pill">
                                {{ $c->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.customers.show', $c) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                                <form action="{{ route('admin.customers.toggle', $c) }}" method="POST"
                                    onsubmit="return confirm('Yakin?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-{{ $c->is_active ? 'danger' : 'success' }}">
                                        <i class="bi bi-{{ $c->is_active ? 'lock' : 'unlock' }}"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $customers->withQueryString()->links() }}</div>
    </div>
</div>
@endsection
