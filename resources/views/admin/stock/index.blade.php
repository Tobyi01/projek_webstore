@extends('layouts.admin')

@section('title', 'Stok & Mutasi - NesiaStore')
@section('page-heading', 'Stok')

@section('content')
<div class="row g-4">
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Penyesuaian stok</h2>

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

                <form method="POST" action="{{ route('admin.stock.adjust') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Produk</label>
                        <select name="product_id" class="form-select" required>
                            <option value="">Pilih produk</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }} (stok: {{ $product->stock }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jenis perubahan</label>
                        <select name="type" class="form-select" required>
                            <option value="in">Stok masuk</option>
                            <option value="out">Stok keluar</option>
                            <option value="adjustment">Penyesuaian stok</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah</label>
                        <input type="number" name="quantity" min="1" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Update stok</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Produk dengan stok minim</h2>
                <div class="list-group list-group-flush">
                    @forelse ($lowStockProducts as $product)
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <div class="fw-semibold">{{ $product->name }}</div>
                                <small class="text-muted">{{ $product->supplier?->name ?? 'Supplier belum diatur' }}</small>
                            </div>
                            <span class="badge text-bg-warning">{{ $product->stock }} tersisa</span>
                        </div>
                    @empty
                        <div class="text-muted">Semua produk sudah berada di level aman.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Mutasi stok terakhir</h2>
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Jenis</th>
                                <th>Jumlah</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($movements as $movement)
                                <tr>
                                    <td>{{ $movement->product?->name ?? 'Produk dihapus' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $movement->type === 'out' ? 'danger' : ($movement->type === 'in' ? 'success' : 'secondary') }}-subtle text-{{ $movement->type === 'out' ? 'danger' : ($movement->type === 'in' ? 'success' : 'secondary') }} rounded-pill">
                                            {{ $movement->type }}
                                        </span>
                                    </td>
                                    <td>{{ $movement->quantity }}</td>
                                    <td>{{ $movement->notes ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Belum ada mutasi stok.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
