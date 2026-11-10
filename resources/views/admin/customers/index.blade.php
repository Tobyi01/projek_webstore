@extends('layouts.admin')

@section('title', 'Pelanggan & Loyalitas - NesiaStore')
@section('page-heading', 'Pelanggan')

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">{{ isset($customer) ? 'Edit pelanggan' : 'Tambah pelanggan' }}</h2>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ isset($customer) ? route('admin.customers.update', $customer) : route('admin.customers.store') }}">
                    @csrf
                    @if (isset($customer))
                        @method('PUT')
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Nama pelanggan</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $customer->name ?? '') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Telepon</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $customer->phone ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $customer->email ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="address" class="form-control" rows="3">{{ old('address', $customer->address ?? '') }}</textarea>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Poin</label>
                            <input type="number" name="points" min="0" class="form-control" value="{{ old('points', $customer->points ?? 0) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <div class="form-check mt-2">
                                <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', $customer->is_active ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label">Aktif</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">{{ isset($customer) ? 'Update' : 'Simpan' }}</button>
                        @if (isset($customer))
                            <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary">Batal</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="h5 mb-0">Daftar pelanggan</h2>
                    <span class="badge bg-success-subtle text-success">{{ $customers->total() }} pelanggan</span>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Kontak</th>
                                <th>Poin</th>
                                <th>Total belanja</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($customers as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td>
                                        <div>{{ $item->phone ?? '—' }}</div>
                                        <small class="text-muted">{{ $item->email ?? '—' }}</small>
                                    </td>
                                    <td><span class="badge text-bg-warning">{{ number_format($item->points, 0, ',', '.') }} poin</span></td>
                                    <td>Rp {{ number_format((float) $item->total_spent, 0, ',', '.') }}</td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.customers.edit', $item) }}" class="btn btn-outline-primary">Edit</a>
                                            <form method="POST" action="{{ route('admin.customers.destroy', $item) }}" onsubmit="return confirm('Hapus pelanggan ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada pelanggan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $customers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
