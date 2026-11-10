@extends('layouts.admin')

@section('title', 'Laporan Bisnis - NesiaStore')
@section('page-heading', 'Laporan')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Pendapatan bulan ini</div>
                <h3 class="mt-2 mb-0">Rp {{ number_format($monthRevenue, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Transaksi bulan ini</div>
                <h3 class="mt-2 mb-0">{{ number_format($monthTransactions, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Produk terdaftar</div>
                <h3 class="mt-2 mb-0">{{ number_format($totalProducts, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Supplier aktif</div>
                <h3 class="mt-2 mb-0">{{ number_format($suppliers->count(), 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Produk terlaris</h2>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Terjual</th>
                                <th>Penjualan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($topProducts as $product)
                                <tr>
                                    <td>{{ $product->product_name }}</td>
                                    <td>{{ number_format($product->total_quantity, 0, ',', '.') }} pcs</td>
                                    <td>Rp {{ number_format((float) $product->total_sales, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">Belum ada data penjualan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Stok minim</h2>
                <div class="list-group list-group-flush">
                    @forelse ($lowStockProducts as $product)
                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-semibold">{{ $product->name }}</div>
                                <small class="text-muted">{{ $product->category ?: 'Tanpa kategori' }}</small>
                            </div>
                            <span class="badge text-bg-warning">{{ $product->stock }} pcs</span>
                        </div>
                    @empty
                        <div class="text-muted">Semua produk aman.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-1">
    <div class="col-lg-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Transaksi terbaru</h2>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Transaksi</th>
                                <th>Kasir</th>
                                <th>Waktu</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentSales as $sale)
                                <tr>
                                    <td>{{ $sale->transaction_number }}</td>
                                    <td>{{ $sale->user?->name ?? '—' }}</td>
                                    <td>{{ $sale->sold_at?->format('d M Y H:i') }}</td>
                                    <td>Rp {{ number_format((float) $sale->total, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Belum ada transaksi.</td>
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
