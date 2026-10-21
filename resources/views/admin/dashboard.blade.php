@extends('layouts.admin')

@section('title', 'Dashboard Admin - NesiaStore')
@section('page-heading', 'Dashboard')

@section('content')
<div class="store-dashboard">
    <header class="dashboard-heading">
        <div><h1>Dashboard</h1><p>Ringkasan operasional NesiaStore.</p></div>
        <a class="report-link" href="{{ route('transactions.index') }}"><i class="bi bi-file-earmark-bar-graph" aria-hidden="true"></i> Lihat riwayat</a>
    </header>

    <section class="metric-grid" aria-label="Ringkasan toko">
        <article class="metric-card metric-blue"><div><span>PENJUALAN BULAN INI</span><strong>Rp {{ number_format($monthRevenue, 0, ',', '.') }}</strong></div><i class="bi bi-calendar2-check" aria-hidden="true"></i></article>
        <article class="metric-card metric-green"><div><span>PENJUALAN TAHUN INI</span><strong>Rp {{ number_format($yearRevenue, 0, ',', '.') }}</strong></div><i class="bi bi-currency-exchange" aria-hidden="true"></i></article>
        <article class="metric-card metric-cyan"><div><span>TRANSAKSI BULAN INI</span><strong>{{ number_format($monthTransactionCount, 0, ',', '.') }}</strong></div><i class="bi bi-receipt" aria-hidden="true"></i></article>
        <article class="metric-card metric-gold"><div><span>STOK MENIPIS</span><strong>{{ number_format($lowStockCount, 0, ',', '.') }} <small>produk</small></strong></div><i class="bi bi-exclamation-triangle" aria-hidden="true"></i></article>
    </section>

    <section class="chart-grid" aria-label="Grafik penjualan">
        <article class="dashboard-panel revenue-panel">
            <header class="panel-heading"><div><h2>Ringkasan penjualan</h2><p>Pendapatan enam bulan terakhir</p></div><span class="panel-menu" aria-hidden="true"><i class="bi bi-three-dots-vertical"></i></span></header>
            <div class="revenue-chart" role="img" aria-label="Grafik pendapatan enam bulan terakhir">
                @foreach ($monthlySales as $month)
                    <div class="revenue-column">
                        <span class="revenue-value">{{ $month['revenue'] > 0 ? number_format($month['revenue'] / 1000000, 1, ',', '.').' jt' : 'Rp 0' }}</span>
                        <div class="revenue-track"><span style="--bar-height: {{ $maxMonthlyRevenue > 0 ? max(4, $month['revenue'] / $maxMonthlyRevenue * 100) : 0 }}%"></span></div>
                        <span class="revenue-month">{{ $month['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </article>

        <article class="dashboard-panel payment-panel">
            <header class="panel-heading"><div><h2>Metode pembayaran</h2><p>Transaksi bulan ini</p></div></header>
            <div class="payment-summary">
                <div class="payment-donut" role="img" aria-label="Komposisi metode pembayaran" style="--payment-gradient: {{ $paymentGradient }}"><span>{{ number_format(count($paymentSlices), 0, ',', '.') }}<small>metode</small></span></div>
                @if (count($paymentSlices))
                    <ul class="payment-legend">
                        @foreach ($paymentSlices as $slice)
                            <li><i style="--legend-color: {{ $slice['color'] }}"></i><span>{{ $slice['label'] }}</span><strong>Rp {{ number_format($slice['total'], 0, ',', '.') }}</strong></li>
                        @endforeach
                    </ul>
                @else
                    <p class="empty-chart">Belum ada pembayaran bulan ini.</p>
                @endif
            </div>
        </article>
    </section>

    <section class="detail-grid">
        <article class="dashboard-panel table-panel">
            <header class="panel-heading"><div><h2>Transaksi terbaru</h2><p>{{ number_format($productsCount, 0, ',', '.') }} produk terdaftar</p></div><a href="{{ route('transactions.index') }}">Semua transaksi <i class="bi bi-arrow-right" aria-hidden="true"></i></a></header>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead><tr><th>TRANSAKSI</th><th>PELANGGAN</th><th>WAKTU</th><th>METODE</th><th class="text-end">TOTAL</th></tr></thead>
                    <tbody>
                        @forelse ($recentSales as $sale)
                            <tr>
                                <td><strong>{{ $sale->transaction_number }}</strong></td>
                                <td>{{ $sale->customer }}</td>
                                <td>{{ $sale->sold_at->format('d/m/Y H:i') }}</td>
                                <td><span class="method-badge">{{ $sale->payment_method }}</span></td>
                                <td class="text-end"><strong>Rp {{ number_format($sale->total, 0, ',', '.') }}</strong></td>
                            </tr>
                        @empty
                            <tr><td class="table-empty" colspan="5">Belum ada transaksi yang tercatat.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </article>

        <article class="dashboard-panel stock-panel">
            <header class="panel-heading"><div><h2>Perhatian stok</h2><p>Produk dengan stok 5 atau kurang</p></div><a href="{{ route('products.index') }}" aria-label="Lihat semua produk"><i class="bi bi-arrow-up-right" aria-hidden="true"></i></a></header>
            @forelse ($lowStockProducts as $product)
                <div class="stock-row"><span class="stock-icon"><i class="bi bi-box-seam" aria-hidden="true"></i></span><span class="stock-name">{{ $product->name }}<small>{{ $product->category ?: 'Tanpa kategori' }}</small></span><strong class="stock-count">{{ $product->stock }} tersisa</strong></div>
            @empty
                <div class="stock-empty"><i class="bi bi-check2-circle" aria-hidden="true"></i><span>Semua stok produk mencukupi.</span></div>
            @endforelse
            <a class="add-product-link" href="{{ route('products.create') }}"><i class="bi bi-plus-lg" aria-hidden="true"></i> Tambah produk</a>
        </article>
    </section>
</div>

<style>
    .store-dashboard { --dash-blue: #496de5; --dash-ink: #4a5265; --dash-muted: #8b94a6; --dash-line: #e9edf4; color: var(--dash-ink); }
    .dashboard-heading { align-items: center; display: flex; justify-content: space-between; margin-bottom: 1.15rem; }
    .dashboard-heading h1 { color: #454c5e; font-size: 1.35rem; font-weight: 600; margin: 0; }
    .dashboard-heading p { color: var(--dash-muted); font-size: .7rem; margin: .25rem 0 0; }
    .report-link { align-items: center; background: var(--dash-blue); border-radius: .25rem; box-shadow: 0 .2rem .45rem rgba(73,109,229,.2); color: #fff; display: inline-flex; font-size: .65rem; gap: .4rem; padding: .55rem .7rem; text-decoration: none; }
    .report-link:hover { background: #3559cf; color: #fff; }
    .metric-grid { display: grid; gap: .8rem; grid-template-columns: repeat(4, minmax(0, 1fr)); margin-bottom: .9rem; }
    .metric-card { align-items: center; background: #fff; border: 1px solid var(--dash-line); border-left: 3px solid var(--metric-color); border-radius: .3rem; box-shadow: 0 .25rem .7rem rgba(38,55,91,.06); display: flex; justify-content: space-between; min-height: 4.6rem; padding: .75rem .9rem; }
    .metric-card > div { min-width: 0; }.metric-card span { color: var(--metric-color); display: block; font-size: .52rem; font-weight: 800; letter-spacing: .05em; }.metric-card strong { color: #4a5265; display: block; font-size: .95rem; margin-top: .3rem; overflow-wrap: anywhere; }.metric-card strong small { color: var(--dash-muted); font-size: .62rem; font-weight: 500; }.metric-card > i { color: #d8deea; font-size: 1.25rem; margin-left: .35rem; }
    .metric-blue { --metric-color: #496de5; }.metric-green { --metric-color: #18b989; }.metric-cyan { --metric-color: #39aeca; }.metric-gold { --metric-color: #e5aa35; }
    .chart-grid, .detail-grid { display: grid; gap: .9rem; grid-template-columns: minmax(0, 1.65fr) minmax(16rem, 1fr); margin-bottom: .9rem; }
    .dashboard-panel { background: #fff; border: 1px solid var(--dash-line); border-radius: .3rem; box-shadow: 0 .25rem .7rem rgba(38,55,91,.06); min-width: 0; }
    .panel-heading { align-items: center; border-bottom: 1px solid #eff2f7; display: flex; justify-content: space-between; min-height: 3.3rem; padding: .7rem .85rem; }
    .panel-heading h2 { color: #526078; font-size: .72rem; font-weight: 700; margin: 0; }.panel-heading p { color: #9aa3b2; font-size: .56rem; margin: .2rem 0 0; }.panel-heading > a { color: var(--dash-blue); font-size: .56rem; text-decoration: none; white-space: nowrap; }.panel-menu { color: #bbc3d0; font-size: .75rem; }
    .revenue-chart { align-items: stretch; display: flex; gap: .55rem; height: 13rem; justify-content: space-around; padding: 1rem .9rem .6rem; }
    .revenue-column { align-items: center; display: flex; flex: 1; flex-direction: column; justify-content: end; min-width: 0; }.revenue-value { color: #8792a6; font-size: .5rem; min-height: 1rem; white-space: nowrap; }.revenue-track { align-items: end; background: repeating-linear-gradient(to bottom, #fff 0, #fff 2.8rem, #edf0f6 2.85rem, #fff 2.9rem); border-bottom: 1px solid #dfe4ed; display: flex; flex: 1; justify-content: center; margin-top: .35rem; width: 100%; }.revenue-track span { animation: bar-enter .65s ease both; background: linear-gradient(180deg, #57a4ee, #496de5); border-radius: .2rem .2rem 0 0; height: var(--bar-height); max-width: 2.2rem; min-height: 0; width: 62%; }.revenue-month { color: #8d97a9; font-size: .55rem; padding-top: .35rem; text-transform: capitalize; }
    .payment-summary { align-items: center; display: flex; gap: .85rem; justify-content: center; min-height: 13rem; padding: .8rem; }.payment-donut { align-items: center; background: var(--payment-gradient); border-radius: 50%; display: flex; flex: 0 0 8rem; height: 8rem; justify-content: center; position: relative; width: 8rem; }.payment-donut::before { background: #fff; border-radius: 50%; content: ""; inset: 1rem; position: absolute; }.payment-donut > span { color: #59657a; font-size: .9rem; font-weight: 700; position: relative; text-align: center; }.payment-donut small { color: #97a0b0; display: block; font-size: .5rem; font-weight: 500; }.payment-legend { list-style: none; margin: 0; max-width: 100%; padding: 0; }.payment-legend li { align-items: center; display: grid; font-size: .52rem; gap: .35rem; grid-template-columns: .42rem minmax(3rem,1fr); margin: .45rem 0; }.payment-legend li i { background: var(--legend-color); border-radius: 50%; height: .4rem; width: .4rem; }.payment-legend li span { color: #788399; }.payment-legend li strong { color: #59657a; font-size: .48rem; grid-column: 2; }.empty-chart { color: var(--dash-muted); font-size: .62rem; text-align: center; }
    .table-panel { overflow: hidden; }.admin-table { border-collapse: collapse; font-size: .58rem; min-width: 34rem; width: 100%; }.admin-table th { background: #fafbfe; color: #929bad; font-size: .48rem; font-weight: 700; letter-spacing: .04em; padding: .65rem .7rem; text-align: left; }.admin-table td { border-top: 1px solid #f0f2f6; color: #707b8f; padding: .65rem .7rem; white-space: nowrap; }.admin-table td strong { color: #566176; font-weight: 600; }.table-empty { padding: 1.5rem !important; text-align: center; }
    .method-badge { background: #edf2ff; border-radius: 2rem; color: #496de5; display: inline-block; font-size: .5rem; padding: .25rem .45rem; }
    .stock-row { align-items: center; border-bottom: 1px solid #f0f2f6; display: flex; gap: .55rem; padding: .65rem .8rem; }.stock-icon { align-items: center; background: #fff4df; border-radius: .25rem; color: #d59a24; display: flex; flex: 0 0 1.8rem; height: 1.8rem; justify-content: center; }.stock-name { color: #657087; flex: 1; font-size: .59rem; min-width: 0; overflow-wrap: anywhere; }.stock-name small { color: #a0a8b6; display: block; font-size: .5rem; margin-top: .12rem; }.stock-count { background: #fff4e5; border-radius: 2rem; color: #b27b19; font-size: .48rem; padding: .3rem .45rem; white-space: nowrap; }.stock-empty { align-items: center; color: #6c9d7a; display: flex; font-size: .58rem; gap: .4rem; justify-content: center; min-height: 6rem; }.stock-empty i { font-size: .9rem; }.add-product-link { border-top: 1px solid #eff2f7; color: var(--dash-blue); display: block; font-size: .56rem; padding: .65rem .8rem; text-decoration: none; }.add-product-link:hover { background: #f8faff; }
    @keyframes bar-enter { from { transform: scaleY(.15); transform-origin: bottom; } to { transform: scaleY(1); transform-origin: bottom; } }
    @media (max-width: 1100px) { .metric-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }.payment-summary { flex-wrap: wrap; }.payment-legend { width: 100%; } }
    @media (max-width: 760px) { .chart-grid, .detail-grid { grid-template-columns: 1fr; }.dashboard-heading { align-items: start; gap: .7rem; }.revenue-chart { height: 11rem; }.payment-summary { flex-wrap: nowrap; }.payment-legend { width: auto; } }
    @media (max-width: 480px) { .dashboard-heading { flex-direction: column; }.metric-card { min-height: 4.2rem; padding: .6rem; }.metric-card strong { font-size: .78rem; }.metric-card span { font-size: .45rem; }.metric-card > i { font-size: 1rem; }.revenue-chart { gap: .25rem; padding-left: .45rem; padding-right: .45rem; }.revenue-value { font-size: .42rem; }.payment-summary { gap: .6rem; }.payment-donut { flex-basis: 6.5rem; height: 6.5rem; width: 6.5rem; }.payment-donut::before { inset: .8rem; }.payment-legend li { font-size: .48rem; } }
    @media (prefers-reduced-motion: reduce) { .revenue-track span { animation: none; } }
    .store-dashboard { --dash-blue: #3974df; --dash-ink: #26354d; --dash-muted: #7c8ba1; --dash-line: #e8edf4; }
    .dashboard-heading { margin-bottom: 1.5rem; }
    .dashboard-heading h1 { color: #1b2b44; font-size: 1.65rem; font-weight: 750; letter-spacing: -.035em; }
    .dashboard-heading p { color: #8290a4; font-size: .82rem; margin-top: .35rem; }
    .report-link { border-radius: .65rem; box-shadow: 0 .35rem .8rem rgba(57,116,223,.18); font-size: .76rem; font-weight: 700; padding: .7rem .9rem; }
    .metric-grid { gap: 1rem; margin-bottom: 1rem; }
    .metric-card { border: 1px solid var(--dash-line); border-left: 0; border-radius: .85rem; box-shadow: 0 .35rem 1.2rem rgba(35,55,90,.045); min-height: 6rem; padding: 1rem 1.1rem; position: relative; }
    .metric-card::before { background: var(--metric-color); border-radius: 0 .3rem .3rem 0; content: ""; height: 2.5rem; left: 0; position: absolute; top: 1rem; width: .22rem; }
    .metric-card span { font-size: .62rem; letter-spacing: .07em; }
    .metric-card strong { color: #25354e; font-size: 1.18rem; font-weight: 750; margin-top: .4rem; }
    .metric-card > i { background: color-mix(in srgb, var(--metric-color) 10%, white); border-radius: .75rem; color: var(--metric-color); font-size: 1.25rem; margin-left: .5rem; padding: .7rem; }
    .chart-grid, .detail-grid { gap: 1rem; margin-bottom: 1rem; }
    .dashboard-panel { border: 1px solid var(--dash-line); border-radius: .9rem; box-shadow: 0 .35rem 1.2rem rgba(35,55,90,.045); overflow: hidden; }
    .panel-heading { min-height: 4rem; padding: .85rem 1rem; }
    .panel-heading h2 { color: #2d3d55; font-size: .84rem; font-weight: 750; }
    .panel-heading p { color: #8794a7; font-size: .66rem; }
    .panel-heading > a { font-size: .68rem; font-weight: 700; }
    .revenue-chart { height: 14rem; padding: 1.15rem 1rem .7rem; }
    .revenue-value, .revenue-month { font-size: .62rem; }
    .revenue-track span { border-radius: .35rem .35rem 0 0; }
    .payment-summary { gap: 1.1rem; min-height: 14rem; }
    .payment-legend li { font-size: .62rem; gap: .45rem; grid-template-columns: .5rem minmax(4rem,1fr); }
    .payment-legend li strong { font-size: .58rem; }
    .admin-table { font-size: .7rem; min-width: 38rem; }
    .admin-table th { font-size: .58rem; padding: .8rem .85rem; }
    .admin-table td { padding: .8rem .85rem; }
    .admin-table td strong { color: #33445d; }
    .method-badge { font-size: .6rem; padding: .32rem .55rem; }
    @media (max-width: 600px) {
        .dashboard-heading h1 { font-size: 1.4rem; }
        .metric-card { min-height: 5.4rem; padding: .75rem; }
        .metric-card span { font-size: .53rem; }
        .metric-card strong { font-size: .95rem; }
        .metric-card > i { font-size: 1rem; padding: .55rem; }
    }
</style>
@endsection