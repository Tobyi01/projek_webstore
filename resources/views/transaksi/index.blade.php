@extends('layouts.admin')

@section('title', 'Riwayat Transaksi - NesiaStore')

@section('content')
<style>
    .history-page { --history-ink: #122b4e; --history-blue: #1769dc; --history-muted: #7587a2; --history-line: #e2eaf4; color: var(--history-ink); }
    .history-hero { align-items: end; animation: history-enter .5s ease both; background: linear-gradient(112deg, #102c62, #1769bd); border-radius: .7rem; color: #fff; display: flex; justify-content: space-between; gap: 1.5rem; margin-bottom: 1.15rem; overflow: hidden; padding: 1.35rem 1.5rem; position: relative; }
    .history-hero::after { border: 1px solid rgba(255,255,255,.14); border-radius: 50%; content: ""; height: 17rem; position: absolute; right: -3rem; top: -10rem; width: 17rem; }
    .history-hero > div, .period-form { position: relative; z-index: 1; }
    .history-kicker { color: #b9d9ff; font-size: .6rem; font-weight: 800; letter-spacing: .12em; }
    .history-hero h1 { font-size: 1.7rem; font-weight: 800; margin: .35rem 0 .2rem; }
    .history-hero p { color: #d2e4fb; font-size: .72rem; margin: 0; }
    .period-form { align-items: center; display: flex; gap: .45rem; }
    .period-select { background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.32); border-radius: .4rem; color: #fff; font-size: .65rem; min-height: 2.2rem; padding: .4rem .55rem; }
    .period-select option { color: #18375e; }
    .period-submit { align-items: center; background: #e4f3ff; border: 0; border-radius: .4rem; color: #144b8c; display: flex; font-size: .7rem; font-weight: 800; height: 2.2rem; justify-content: center; width: 2.3rem; }
    .period-tabs { border-bottom: 1px solid var(--history-line); display: flex; gap: .3rem; margin-bottom: 1rem; }
    .period-tab { border-bottom: 2px solid transparent; color: #7889a1; font-size: .7rem; font-weight: 700; padding: .65rem .85rem; text-decoration: none; transition: border-color .2s, color .2s; }
    .period-tab:hover, .period-tab.active { border-color: var(--history-blue); color: var(--history-blue); }
    .history-summary { animation: history-enter .55s .08s ease both; display: grid; gap: .7rem; grid-template-columns: repeat(3, minmax(0, 1fr)); margin-bottom: 1.25rem; }
    .summary-stat { align-items: center; background: #fff; border: 1px solid var(--history-line); border-radius: .55rem; display: flex; gap: .7rem; min-height: 4.5rem; padding: .7rem .85rem; }
    .summary-icon { align-items: center; background: #eaf3ff; border-radius: .45rem; color: var(--history-blue); display: flex; flex: 0 0 2.1rem; height: 2.1rem; justify-content: center; }
    .summary-stat span, .summary-stat strong { display: block; }.summary-stat span { color: var(--history-muted); font-size: .58rem; }.summary-stat strong { color: #18375e; font-size: .9rem; margin-top: .12rem; }
    .history-groups { display: grid; gap: 1rem; }
    .history-group { animation: history-enter .55s ease both; background: #fff; border: 1px solid var(--history-line); border-radius: .55rem; overflow: hidden; }
    .group-heading { align-items: center; background: #f8faff; border-bottom: 1px solid var(--history-line); display: flex; justify-content: space-between; padding: .75rem .9rem; }
    .group-heading strong { color: #244366; font-size: .72rem; }.group-heading span { color: #7386a1; font-size: .62rem; }.group-total { color: #1769bd !important; font-size: .72rem !important; font-weight: 800; }
    .sale-entry { border-bottom: 1px solid #edf1f6; padding: .8rem .9rem; }.sale-entry:last-child { border-bottom: 0; }
    .sale-summary { align-items: center; display: grid; gap: .7rem; grid-template-columns: minmax(7rem, .8fr) minmax(10rem, 1.5fr) minmax(6rem, .8fr) auto; }
    .sale-time { color: #60758f; font-size: .63rem; white-space: nowrap; }.sale-identity strong, .sale-identity small { display: block; }.sale-identity strong { color: #22436c; font-size: .68rem; }.sale-identity small { color: #8191a8; font-size: .58rem; margin-top: .15rem; }
    .payment-badge { background: #edf4ff; border-radius: 2rem; color: #356cae; display: inline-block; font-size: .57rem; font-weight: 700; padding: .35rem .55rem; white-space: nowrap; }.sale-total { color: #193c69; font-size: .72rem; font-weight: 800; text-align: right; white-space: nowrap; }
    .sale-details { margin-top: .55rem; }.sale-details summary { color: var(--history-blue); cursor: pointer; font-size: .6rem; font-weight: 700; list-style-position: inside; }.items-table { border-collapse: collapse; font-size: .6rem; margin-top: .5rem; width: 100%; }.items-table th, .items-table td { border-bottom: 1px solid #edf1f6; padding: .45rem .35rem; text-align: left; }.items-table th { color: #7a8ca4; font-size: .53rem; font-weight: 700; }.items-table td:last-child, .items-table th:last-child { text-align: right; }.sale-foot { color: #70839c; display: flex; flex-wrap: wrap; font-size: .58rem; gap: .8rem; justify-content: end; padding-top: .5rem; }.sale-foot strong { color: #284b73; }
    .history-empty { background: #fff; border: 1px dashed #cbd9eb; border-radius: .55rem; padding: 2.5rem 1rem; text-align: center; }.history-empty i { color: #4b8bd4; font-size: 1.5rem; }.history-empty strong { color: #26466b; display: block; font-size: .8rem; margin-top: .5rem; }.history-empty p { color: var(--history-muted); font-size: .65rem; margin: .3rem 0 0; }
    @keyframes history-enter { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    @media (max-width: 900px) { .history-hero { align-items: start; flex-direction: column; }.period-form { flex-wrap: wrap; }.sale-summary { grid-template-columns: minmax(5rem, .7fr) minmax(8rem, 1fr) auto; }.sale-summary .payment-badge { display: none; } }
    @media (max-width: 600px) { .history-hero { padding: 1.1rem; }.history-hero h1 { font-size: 1.45rem; }.period-select { flex: 1; min-width: 0; }.history-summary { gap: .4rem; }.summary-stat { align-items: start; flex-direction: column; gap: .4rem; min-height: 5.2rem; padding: .6rem; }.summary-icon { flex-basis: 1.7rem; height: 1.7rem; width: 1.7rem; }.summary-stat strong { font-size: .75rem; }.sale-summary { gap: .4rem; grid-template-columns: minmax(0, 1fr) auto; }.sale-time { grid-column: 1 / -1; }.sale-total { grid-column: 2; grid-row: 2; }.sale-identity { grid-column: 1; grid-row: 2; }.sale-foot { justify-content: start; }.group-heading { align-items: start; flex-direction: column; gap: .25rem; } }
    @media (prefers-reduced-motion: reduce) { *, *::before, *::after { animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important; } }
</style>

<div class="history-page">
    <header class="history-hero">
        <div><span class="history-kicker">CATATAN PENJUALAN</span><h1>Riwayat transaksi</h1><p>Telusuri transaksi berdasarkan hari, bulan, atau tahun.</p></div>
        <form class="period-form" method="GET" action="{{ route('transactions.index') }}">
            <input type="hidden" name="period" value="{{ $period }}">
            @if ($period === 'day')
                <input class="period-select" type="date" name="date" value="{{ $start->format('Y-m-d') }}" aria-label="Pilih tanggal">
            @elseif ($period === 'month')
                <input class="period-select" type="month" name="month" value="{{ $start->format('Y-m') }}" aria-label="Pilih bulan">
            @else
                <input class="period-select" type="number" name="year" min="2000" max="2100" value="{{ $start->format('Y') }}" aria-label="Pilih tahun">
            @endif
            <button class="period-submit" type="submit" aria-label="Terapkan filter" title="Terapkan filter"><i class="bi bi-arrow-right" aria-hidden="true"></i></button>
        </form>
    </header>

    <nav class="period-tabs" aria-label="Filter periode">
        <a class="period-tab {{ $period === 'day' ? 'active' : '' }}" href="{{ route('transactions.index', ['period' => 'day']) }}" @if ($period === 'day') aria-current="page" @endif>Hari</a>
        <a class="period-tab {{ $period === 'month' ? 'active' : '' }}" href="{{ route('transactions.index', ['period' => 'month']) }}" @if ($period === 'month') aria-current="page" @endif>Bulan</a>
        <a class="period-tab {{ $period === 'year' ? 'active' : '' }}" href="{{ route('transactions.index', ['period' => 'year']) }}" @if ($period === 'year') aria-current="page" @endif>Tahun</a>
    </nav>

    <section class="history-summary" aria-label="Ringkasan {{ strtolower($periodTitle) }}">
        <div class="summary-stat"><span class="summary-icon"><i class="bi bi-receipt" aria-hidden="true"></i></span><div><span>Transaksi</span><strong>{{ number_format($saleCount, 0, ',', '.') }}</strong></div></div>
        <div class="summary-stat"><span class="summary-icon"><i class="bi bi-box-seam" aria-hidden="true"></i></span><div><span>Barang terjual</span><strong>{{ number_format($itemCount, 0, ',', '.') }}</strong></div></div>
        <div class="summary-stat"><span class="summary-icon"><i class="bi bi-currency-exchange" aria-hidden="true"></i></span><div><span>Total penjualan</span><strong>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</strong></div></div>
    </section>

    @if ($groups->isEmpty())
        <div class="history-empty"><i class="bi bi-calendar2-x" aria-hidden="true"></i><strong>Belum ada transaksi {{ strtolower($periodTitle) }} ini</strong><p>Transaksi yang berhasil dibayar akan muncul di sini.</p></div>
    @else
        <div class="history-groups">
            @foreach ($groups as $group)
                <section class="history-group">
                    <header class="group-heading"><div><strong>{{ $group['label'] }}</strong> <span>{{ number_format($group['sales']->count(), 0, ',', '.') }} transaksi · {{ number_format($group['items'], 0, ',', '.') }} barang</span></div><span class="group-total">Rp {{ number_format($group['total'], 0, ',', '.') }}</span></header>
                    @foreach ($group['sales'] as $sale)
                        <article class="sale-entry">
                            <div class="sale-summary">
                                <time class="sale-time" datetime="{{ $sale->sold_at->toIso8601String() }}">{{ $sale->sold_at->format('H:i') }} · {{ $sale->transaction_number }}</time>
                                <div class="sale-identity"><strong>{{ $sale->customer }}</strong><small>{{ number_format($sale->items->sum('quantity'), 0, ',', '.') }} barang</small></div>
                                <span class="payment-badge">{{ $sale->payment_method }}</span>
                                <strong class="sale-total">Rp {{ number_format($sale->total, 0, ',', '.') }}</strong>
                            </div>
                            <details class="sale-details">
                                <summary>Lihat rincian barang</summary>
                                <table class="items-table"><thead><tr><th>Barang</th><th>Qty</th><th>Harga</th><th>Jumlah</th></tr></thead><tbody>
                                    @foreach ($sale->items as $item)
                                        <tr><td>{{ $item->product_name }}</td><td>{{ $item->quantity }}</td><td>Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td><td>Rp {{ number_format($item->line_total, 0, ',', '.') }}</td></tr>
                                    @endforeach
                                </tbody></table>
                                <div class="sale-foot"><span>Subtotal <strong>Rp {{ number_format($sale->subtotal, 0, ',', '.') }}</strong></span><span>{{ $sale->payment_method }} <strong>Rp {{ number_format($sale->paid, 0, ',', '.') }}</strong></span><span>Kembalian <strong>Rp {{ number_format($sale->change, 0, ',', '.') }}</strong></span></div>
                            </details>
                        </article>
                    @endforeach
                </section>
            @endforeach
        </div>
    @endif
</div>
@endsection