@extends('layouts.admin')

@section('title', 'Supplier - NesiaStore')
@section('page-heading', 'Supplier')

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">{{ isset($supplier) ? 'Edit supplier' : 'Tambah supplier' }}</h2>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ isset($supplier) ? route('admin.suppliers.update', $supplier) : route('admin.suppliers.store') }}">
                    @csrf
                    @if (isset($supplier))
                        @method('PUT')
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Nama supplier</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $supplier->name ?? '') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Telepon</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $supplier->phone ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $supplier->email ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="address" class="form-control" rows="3">{{ old('address', $supplier->address ?? '') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $supplier->notes ?? '') }}</textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">{{ isset($supplier) ? 'Update' : 'Simpan' }}</button>
                        @if (isset($supplier))
                            <a href="{{ route('admin.suppliers.index') }}" class="btn btn-outline-secondary">Batal</a>
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
                    <h2 class="h5 mb-0">Daftar supplier</h2>
                    <span class="badge bg-primary-subtle text-primary">{{ $suppliers->total() }} supplier</span>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Kontak</th>
                                <th>Alamat</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($suppliers as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td>
                                        <div>{{ $item->phone ?? '—' }}</div>
                                        <small class="text-muted">{{ $item->email ?? '—' }}</small>
                                    </td>
                                    <td>{{ $item->address ?? '—' }}</td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.suppliers.edit', $item) }}" class="btn btn-outline-primary">Edit</a>
                                            <form method="POST" action="{{ route('admin.suppliers.destroy', $item) }}" onsubmit="return confirm('Hapus supplier ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Belum ada supplier.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $suppliers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
