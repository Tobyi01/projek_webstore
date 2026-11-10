@extends('layouts.admin')

@section('title', 'Kasir - NesiaStore')

@section('content')
<style>
    .pos-page { --pos-blue: #1769ed; --pos-ink: #102858; --pos-muted: #74819c; background: #f4f7fb; margin: -1.5rem; min-height: calc(100vh - 3rem); padding: 1.25rem; }
    .pos-header { display: flex; justify-content: space-between; gap: 1rem; align-items: end; margin-bottom: 1rem; }
    .pos-header h1 { color: var(--pos-ink); font-size: 1.35rem; font-weight: 800; margin: 0; }
    .pos-header p { color: var(--pos-muted); font-size: .78rem; margin: .25rem 0 0; }
    .transaction-meta { color: var(--pos-muted); font-size: .72rem; text-align: right; }
    .transaction-meta strong { color: var(--pos-ink); display: block; font-size: .9rem; }
    .pos-grid { display: grid; grid-template-columns: minmax(0, 1fr) 22rem; gap: 1rem; align-items: start; }
    .pos-card { background: #fff; border: 1px solid #e3e9f2; border-radius: .55rem; box-shadow: 0 .35rem 1.2rem rgba(27, 65, 123, .06); overflow: visible; }
    .pos-card + .pos-card { margin-top: 1rem; }
    .pos-card-title { border-bottom: 1px solid #edf1f6; color: var(--pos-ink); font-size: .82rem; font-weight: 800; padding: .85rem 1rem; }
    .pos-card-body { padding: 1rem; }
    .shift-panel { background: #fff; border: 1px solid #e1e9f4; border-radius: 1rem; box-shadow: 0 12px 32px rgba(27,65,123,.07); margin-bottom: 1.15rem; overflow: hidden; }
    .shift-panel h2 { color: #152b4b; font-size: 1rem; font-weight: 800; letter-spacing: -.025em; margin: 0 0 .35rem; }
    .shift-panel p { color: #71809a; font-size: .76rem; line-height: 1.55; margin: 0; }
    .shift-panel-head { align-items: center; background: linear-gradient(110deg, #102d54, #194b85); color: #fff; display: flex; gap: .8rem; padding: 1rem 1.2rem; }
    .shift-panel-head h2 { color: #fff; margin: 0; }
    .shift-panel-head p { color: rgba(235,244,255,.74); margin-top: .2rem; }
    .shift-panel-icon { align-items: center; background: rgba(255,255,255,.13); border: 1px solid rgba(255,255,255,.17); border-radius: .8rem; color: #a9d8ff; display: inline-flex; flex: 0 0 2.7rem; font-size: 1.15rem; height: 2.7rem; justify-content: center; }
    .shift-live-badge { background: rgba(32,201,151,.15); border: 1px solid rgba(95,227,180,.28); border-radius: 999px; color: #a8f0d2; font-size: .65rem; font-weight: 800; letter-spacing: .04em; margin-left: auto; padding: .4rem .65rem; white-space: nowrap; }
    .shift-panel-content { padding: 1rem 1.2rem 1.15rem; }
    .shift-panel form { align-items: end; display: flex; flex-wrap: wrap; gap: .7rem; margin-top: .9rem; }
    .shift-panel label { color: #40536f; display: block; font-size: .7rem; font-weight: 750; margin-bottom: .35rem; }
    .shift-panel input { border: 1px solid #d6dfeb; border-radius: .55rem; min-height: 2.65rem; padding: .55rem .75rem; }
    .shift-panel input:focus { border-color: #4285e9; box-shadow: 0 0 0 3px rgba(23,105,237,.12); outline: 0; }
    .shift-panel .shift-amount { max-width: 19rem; width: min(100%, 19rem); }
    .shift-active { align-items: center; display: flex; flex-wrap: wrap; gap: .55rem 1.2rem; justify-content: space-between; }
    .shift-metrics { display: grid; flex: 1 1 34rem; gap: .65rem; grid-template-columns: repeat(3, minmax(0, 1fr)); margin: 0; }
    .shift-metric { background: #f7f9fd; border: 1px solid #edf1f7; border-radius: .7rem; min-width: 0; padding: .75rem .85rem; }
    .shift-metric span { color: #78869c; display: block; font-size: .64rem; font-weight: 650; }
    .shift-metric strong { color: #172e50; display: block; font-size: .92rem; margin-top: .25rem; overflow-wrap: anywhere; }
    .shift-close-area { background: #fbfcfe; border: 1px solid #e9eef5; border-radius: .8rem; flex: 1 1 19rem; padding: .85rem; }
    .shift-close-area form { margin-top: .65rem; }
    .shift-close-expected { align-items: center; background: #f1f5fb; border-radius: .55rem; color: #65758d; display: flex; flex-wrap: wrap; font-size: .69rem; gap: .3rem .6rem; justify-content: space-between; margin-top: .65rem; padding: .65rem .75rem; }
    .shift-close-expected strong { color: #172e50; font-size: .86rem; }
    .shift-presets { display: flex; flex-wrap: wrap; gap: .4rem; margin-top: .5rem; }
    .shift-presets button { background: #f2f6fc; border: 1px solid #e1e9f4; border-radius: 999px; color: #405a7c; font-size: .65rem; font-weight: 700; padding: .35rem .65rem; }
    .shift-presets button:hover { background: #e7f0ff; border-color: #c6d9f5; color: #1769ed; }
    .shift-open-button { align-items: center; background: linear-gradient(100deg, #1748bd, #1679ec); border: 0; border-radius: .55rem; box-shadow: 0 5px 14px rgba(23,105,237,.22); color: #fff; display: inline-flex; font-size: .75rem; font-weight: 800; gap: .45rem; min-height: 2.65rem; padding: .65rem 1rem; }
    .shift-close-button { align-items: center; background: #fff; border: 1px solid #f0c4c4; border-radius: .55rem; color: #b83c48; display: inline-flex; font-size: .72rem; font-weight: 750; gap: .4rem; min-height: 2.65rem; padding: .6rem .8rem; }
    .shift-close-button:hover { background: #fff3f3; border-color: #e5a5a8; }
    .pos-grid { border: 0; margin: 0; min-inline-size: 0; min-width: 0; padding: 0; width: 100%; }
    .pos-grid[disabled] { opacity: .56; }
    .shift-lock-note { align-items: center; background: #f1f5fb; border: 1px dashed #cbd6e5; border-radius: .8rem; color: #687993; display: flex; font-size: .76rem; gap: .7rem; margin-bottom: .8rem; padding: .8rem 1rem; }
    .shift-lock-note i { color: #4671b5; font-size: 1.05rem; }
    .checkout-summary { align-items: center; display: grid; gap: .8rem; grid-template-columns: minmax(0, 1fr) auto; }
    .checkout-summary .total-box { margin: 0 0 1rem; }
    .checkout-change { background: #d9f3e5; border-radius: .7rem; margin-top: 0; min-width: 7.5rem; padding: .8rem 1rem; }
    .checkout-change .change-label { color: #53617a; font-size: .7rem; }
    .checkout-change .change-value { color: #167447; font-size: 1.2rem; }
    .checkout-change.short-payment { background: #fff0d6; }
    .checkout-change.short-payment .change-label, .checkout-change.short-payment .change-value { color: #a3660d; }
    .payment-box { background: #fff; border: 1px solid #dfe5ee; border-radius: .75rem; padding: 1rem; }
    .payment-label { color: #34445a; font-size: .76rem; margin-bottom: .5rem; }
    .payment-method { background: #f7f9fc; border: 1px solid #d6dfeb; border-radius: .7rem; gap: .4rem; grid-template-columns: repeat(3, minmax(0, 1fr)); padding: .3rem; }
    .payment-method button { border-radius: .55rem; color: #26364e; font-size: .77rem; min-height: 2.65rem; }
    .payment-method button.active { background: linear-gradient(110deg, #1769ed, #2078ef); border-color: #1769ed; box-shadow: 0 3px 8px rgba(23,105,237,.18); color: #fff; }
    .payment-input { border-color: #cfd7e2; border-radius: .65rem; font-size: 1.2rem; min-height: 3.2rem; padding: .6rem .9rem; }
    .quick-payments { gap: .4rem; }
    .quick-payments button { background: #edf0f5; border: 1px solid transparent; border-radius: .6rem; color: #29364a; flex: 1 1 auto; font-size: .72rem; font-weight: 650; min-height: 2.5rem; padding: .55rem .65rem; }
    .quick-payments button:hover { background: #e2eaf7; border-color: #cfdbed; }
    .payment-box .payment-label.mt-3 { margin-top: 1.15rem !important; }
    .payment-help { color: #71809a; font-size: .67rem; line-height: 1.45; margin: .7rem 0 0; }
    .search-row { display: flex; gap: .6rem; }
    .search-wrap { flex: 1; position: relative; }
    .search-wrap input, .customer-grid input, .customer-grid select, .summary-input, .payment-input { border: 1px solid #d6dfeb; border-radius: .35rem; color: var(--pos-ink); min-height: 2.5rem; padding: .55rem .7rem; width: 100%; }
    .search-wrap input:focus, .customer-grid input:focus, .customer-grid select:focus, .summary-input:focus, .payment-input:focus { border-color: var(--pos-blue); box-shadow: 0 0 0 3px rgba(23, 105, 237, .12); outline: 0; }
    .product-results { background: #fff; border: 1px solid #d6dfeb; border-radius: .35rem; display: none; left: 0; max-height: 17rem; overflow-y: auto; position: absolute; right: 0; top: calc(100% + .3rem); z-index: 10; }
    .product-item { align-items: center; border-bottom: 1px solid #edf1f6; cursor: pointer; display: flex; justify-content: space-between; gap: 1rem; padding: .7rem .8rem; }
    .product-item:last-child { border-bottom: 0; }
    .product-item:hover { background: #f0f6ff; }
    .product-item.stock-low { background: #fff8df; }
    .product-item.stock-out { background: #fff0f0; cursor: not-allowed; opacity: .75; }
    .product-item.stock-out:hover { background: #fff0f0; }
    .product-image { background: #f4f7fb; border-radius: .3rem; flex: 0 0 2.8rem; height: 2.8rem; object-fit: cover; width: 2.8rem; }
    .product-image-placeholder { align-items: center; background: #f4f7fb; border-radius: .3rem; color: var(--pos-muted); display: flex; flex: 0 0 2.8rem; height: 2.8rem; justify-content: center; width: 2.8rem; }
    .product-info { align-items: center; display: flex; gap: .65rem; min-width: 0; }
    .product-name { color: var(--pos-ink); font-size: .78rem; font-weight: 700; }
    .product-code, .product-stock { color: var(--pos-muted); font-size: .68rem; }
    .product-stock { font-weight: 700; }
    .product-stock.stock-low { color: #9a671d; }
    .product-stock.stock-out { color: #c62828; }
    .product-price { color: var(--pos-blue); font-size: .76rem; font-weight: 700; white-space: nowrap; }
    .btn-pos { background: var(--pos-blue); border: 0; border-radius: .35rem; color: #fff; font-size: .75rem; font-weight: 700; padding: .65rem 1rem; }
    .customer-grid { display: grid; grid-template-columns: 1fr 1fr; gap: .7rem; margin-top: .8rem; }
    .field-label, .payment-label { color: #52617d; display: block; font-size: .68rem; font-weight: 700; margin-bottom: .35rem; }
    .table-wrap { overflow-x: auto; }
    .cart-table { border-collapse: collapse; min-width: 600px; width: 100%; }
    .cart-table th { background: #f8faff; color: #52617d; font-size: .65rem; padding: .7rem; text-align: left; white-space: nowrap; }
    .cart-table td { border-top: 1px solid #edf1f6; color: var(--pos-ink); font-size: .74rem; padding: .7rem; vertical-align: middle; }
    .cart-table tr.stock-low td { background: #fff8df; }
    .cart-table .text-right { text-align: right; }
    .empty-cart { color: var(--pos-muted); padding: 3.5rem 1rem !important; text-align: center; }
    .qty-control { align-items: center; display: flex; gap: .3rem; }
    .qty-control button { background: #eef4ff; border: 0; border-radius: .25rem; color: var(--pos-blue); font-weight: 800; height: 1.7rem; width: 1.7rem; }
    .qty-control input { border: 1px solid #d6dfeb; border-radius: .25rem; text-align: center; width: 3rem; }
    .remove-btn { background: transparent; border: 0; color: #d9364f; font-size: 1.1rem; }
    .summary-row { align-items: center; color: #52617d; display: flex; font-size: .72rem; justify-content: space-between; margin-bottom: .75rem; }
    .summary-row strong { color: var(--pos-ink); }
    .summary-input { max-width: 6.5rem; min-height: 2rem; padding: .35rem .5rem; text-align: right; }
    .total-box { background: #f1f5fb; border-radius: .35rem; margin: 1rem 0; padding: .85rem; }
    .total-label { color: var(--pos-muted); font-size: .64rem; font-weight: 700; }
    .total-value { color: var(--pos-ink); font-size: 1.45rem; font-weight: 800; margin-top: .15rem; }
    .payment-box { background: #edf6ff; border: 1px solid #cfe2fb; border-radius: .35rem; padding: .8rem; }
    .payment-input { font-size: 1.1rem; font-weight: 800; min-height: 2.75rem; text-align: right; }
    .payment-method { display: grid; gap: .35rem; grid-template-columns: repeat(3, 1fr); }
    .payment-method button { background: #fff; border: 1px solid #d6dfeb; border-radius: .25rem; color: #52617d; font-size: .65rem; padding: .45rem .2rem; }
    .payment-method button.active { background: var(--pos-blue); border-color: var(--pos-blue); color: #fff; }
    .change-box { background: #d9f3e5; border-radius: .3rem; margin-top: .8rem; padding: .65rem; }
    .change-box.short-payment { background: #fff0d6; }
    .change-label { color: #4b8163; font-size: .62rem; font-weight: 700; }
    .short-payment .change-label { color: #9a671d; }
    .change-value { color: #167447; font-size: 1.15rem; font-weight: 800; }
    .short-payment .change-value { color: #a3660d; }
    .quick-payments { display: flex; flex-wrap: wrap; gap: .35rem; margin-top: .55rem; }
    .quick-payments button { background: #fff; border: 1px solid #bfd5ef; border-radius: .25rem; color: var(--pos-blue); font-size: .65rem; padding: .35rem .5rem; }
    .action-area { display: grid; gap: .5rem; grid-template-columns: 1fr 1fr; margin-top: 1rem; }
    .action-area button { border: 0; border-radius: .3rem; color: #fff; font-size: .72rem; font-weight: 800; padding: .7rem .5rem; }
    .btn-hold { background: #f2b705; } .btn-cancel { background: #dc3545; } .btn-pay { background: #1769ed; grid-column: 1 / -1; }
    .receipt-status { background: #eaf7ef; border-radius: .3rem; color: #226b44; display: none; font-size: .67rem; line-height: 1.5; margin-top: .65rem; padding: .6rem; }
    .receipt-status.is-visible { display: block; }.receipt-status.is-error { background: #fff3df; color: #865814; }
    .connection-status { background: #eaf7ef; border-radius: .45rem; color: #226b44; font-size: .72rem; margin-bottom: .8rem; padding: .6rem .8rem; }
    .connection-status.is-offline { background: #fff3df; color: #865814; }
    .print-receipt { display: none; }
    .print-receipt h1, .print-receipt p { margin: 0; }
    .receipt-head { border-bottom: 1px dashed #222; margin-bottom: .65rem; padding-bottom: .65rem; text-align: center; }
    .receipt-head h1 { font-size: 18px; font-weight: 700; }
    .receipt-head p { font-size: 11px; margin-top: 3px; }
    .receipt-meta { font-size: 11px; margin-bottom: .65rem; }
    .receipt-meta div, .receipt-totals div { display: flex; justify-content: space-between; gap: .6rem; }
    .receipt-items { border-bottom: 1px dashed #222; border-top: 1px dashed #222; margin: .55rem 0; padding: .45rem 0; }
    .receipt-item { margin-bottom: .5rem; }.receipt-item:last-child { margin-bottom: 0; }
    .receipt-item-name { font-weight: 700; overflow-wrap: anywhere; }
    .receipt-item-detail { display: flex; justify-content: space-between; gap: .5rem; }
    .receipt-totals { font-size: 11px; }.receipt-totals div { margin: .2rem 0; }
    .receipt-totals .receipt-grand { border-top: 1px solid #222; font-size: 14px; font-weight: 700; margin-top: .45rem; padding-top: .45rem; }
    .receipt-thanks { border-top: 1px dashed #222; font-size: 11px; margin-top: .7rem !important; padding-top: .6rem; text-align: center; }
    @media (max-width: 900px) { .pos-grid { grid-template-columns: 1fr; } .pos-header { align-items: start; } }
    @media (max-width: 680px) { .shift-metrics { flex-basis: 100%; grid-template-columns: 1fr; } .shift-close-area { flex-basis: 100%; } .shift-live-badge { margin-left: 0; } }
    @media (max-width: 520px) { .pos-page { margin: -1rem; padding: .75rem; } .customer-grid { grid-template-columns: 1fr; } .search-row { flex-direction: column; } .pos-header { flex-direction: column; } .transaction-meta { text-align: left; } .shift-panel-head, .shift-panel-content { padding-left: .85rem; padding-right: .85rem; } .shift-panel-head { align-items: flex-start; flex-wrap: wrap; } .shift-close-area { padding: .7rem; } }
    @media print {
        @page { size: 80mm auto; margin: 4mm; }
        body { background: #fff !important; }
        body * { visibility: hidden !important; }
        #printReceipt, #printReceipt * { visibility: visible !important; }
        #printReceipt { display: block !important; left: 0; margin: 0 auto; max-width: 72mm; position: absolute; top: 0; width: 100%; color: #111; font: 12px/1.4 Arial, sans-serif; }
    }
    .pos-page { --pos-blue: #1769ed; --pos-ink: #23354c; --pos-muted: #7d8ca2; background: #f5f7fb; margin: -1.75rem -2rem -2.5rem; min-height: calc(100vh - 4.35rem); padding: 1.75rem 2rem 2.5rem; }
    .pos-header { align-items: center; margin-bottom: 1.3rem; }
    .pos-header h1 { color: #1b2b44; font-size: 1.55rem; letter-spacing: -.035em; }
    .pos-header p { color: #7d8ca2; font-size: .8rem; }
    .transaction-meta { background: #fff; border: 1px solid #e4ece7; border-radius: .8rem; padding: .65rem .85rem; text-align: right; }
    .transaction-meta strong { color: #25354e; font-size: .95rem; }
    .pos-grid { gap: 1.15rem; grid-template-columns: minmax(0, 1fr) 23rem; }
    .pos-card { border: 1px solid #e4eaf2; border-radius: .9rem; box-shadow: 0 .4rem 1.3rem rgba(27,65,100,.055); }
    .pos-card-title { color: #293b53; font-size: .86rem; padding: 1rem 1.1rem; }
    .pos-card-body { padding: 1.1rem; }
    .search-wrap input, .customer-grid input, .customer-grid select, .summary-input, .payment-input { border-color: #dce5e1; border-radius: .55rem; }
    .product-results { border-color: #dce5ee; border-radius: .6rem; box-shadow: 0 .8rem 1.8rem rgba(27,65,100,.12); }
    .product-item { padding: .8rem .9rem; }
    .btn-pos { border-radius: .55rem; font-size: .78rem; }
    .cart-table th { background: #f6f9f7; color: #63748a; }
    .cart-table td { border-top-color: #edf1ef; }
    .total-box { background: #f0f4ff; border-radius: .8rem; padding: 1rem; }
    .total-value { color: #102858; font-size: 1.7rem; }
    .action-area button { border-radius: .5rem; }
    @media (max-width: 480px) { .checkout-summary { align-items: stretch; grid-template-columns: 1fr; } .checkout-change { align-items: center; display: flex; justify-content: space-between; } }
    @media (max-width: 900px) { .pos-grid { grid-template-columns: 1fr; } }
    @media (max-width: 767.98px) { .pos-page { margin: -1.1rem -.85rem -1.5rem; padding: 1rem .85rem 1.5rem; } }
    @media (max-width: 520px) { .pos-page { margin: -.75rem; padding: .75rem; } }
</style>

<div class="pos-page">
    <header class="pos-header"><div><h1>NesiaStore Kasir</h1><p>Jl. Contoh No. 123 &bull; Jember &bull; Telp. 0812-xxxx-xxxx</p></div><div class="transaction-meta"><span>No. Transaksi</span><strong id="transactionNumber"></strong><span id="currentDate"></span></div></header>
    @if (session('status'))
        <div class="alert alert-success" role="status">{{ session('status') }}</div>
    @endif
    @if (session('shift_warning'))
        <div class="alert alert-warning" role="alert">{{ session('shift_warning') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif
    @if ($currentShift)
        <section class="shift-panel" aria-label="Shift kasir aktif">
            <div class="shift-panel-head">
                <span class="shift-panel-icon" aria-hidden="true"><i class="bi bi-clock-history"></i></span>
                <div>
                    <h2>Shift kasir aktif</h2>
                    <p>Dimulai {{ $currentShift->opened_at->format('d M Y, H:i') }}</p>
                </div>
                <span class="shift-live-badge"><i class="bi bi-circle-fill me-1" aria-hidden="true"></i> Berjalan</span>
            </div>
            <div class="shift-panel-content">
                <div class="shift-active">
                    <div class="shift-metrics">
                        <div class="shift-metric">
                            <span>Modal awal</span>
                            <strong>Rp {{ number_format((float) $currentShift->opening_cash, 0, ',', '.') }}</strong>
                        </div>
                        <div class="shift-metric">
                            <span>Kas seharusnya</span>
                            <strong>Rp {{ number_format((float) $currentShift->opening_cash + $shiftCashSales, 0, ',', '.') }}</strong>
                        </div>
                        <div class="shift-metric">
                            <span>Transaksi tercatat</span>
                            <strong>{{ number_format($shiftTransactionCount, 0, ',', '.') }} transaksi</strong>
                        </div>
                    </div>

                    <div class="shift-close-area" data-expected-cash="{{ (float) $currentShift->opening_cash + $shiftCashSales }}">
                        <label>Tutup shift otomatis</label>
                        <p>Kas akhir dihitung otomatis dari modal awal ditambah seluruh penjualan tunai pada shift ini.</p>
                        <div class="shift-close-expected">
                            <span>Kas akhir tercatat</span>
                            <strong>Rp {{ number_format((float) $currentShift->opening_cash + $shiftCashSales, 0, ',', '.') }}</strong>
                        </div>
                        <form method="POST" action="{{ route('kasir.shift.close', $currentShift) }}">
                            @csrf
                            <button class="shift-close-button" type="submit" onclick="return confirm('Tutup shift dan sesuaikan kas akhir berdasarkan penjualan tunai?');"><i class="bi bi-lock" aria-hidden="true"></i> Tutup shift &amp; sesuaikan kas</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    @else
        <section class="shift-panel" aria-label="Buka shift kasir">
            <div class="shift-panel-head">
                <span class="shift-panel-icon" aria-hidden="true"><i class="bi bi-safe2"></i></span>
                <div>
                    <h2>Mulai shift kasir</h2>
                    <p>Catat uang modal sebelum mulai melayani transaksi.</p>
                </div>
                <span class="shift-live-badge"><i class="bi bi-shield-lock me-1" aria-hidden="true"></i> Perlu dibuka</span>
            </div>
            <div class="shift-panel-content">
                <p>Hitung uang yang tersedia di laci kasir, lalu masukkan jumlahnya. Kasir dan transaksi akan tercatat dalam shift ini.</p>
                <form method="POST" action="{{ route('kasir.shift.start') }}">
                    @csrf
                    <div>
                        <label for="opening_cash">Uang modal awal</label>
                        <input class="shift-amount" id="opening_cash" name="opening_cash" type="number" min="0" step="100" placeholder="Contoh: 200.000" value="{{ old('opening_cash') }}" required>
                        <div class="shift-presets" aria-label="Nominal modal cepat">
                            <button type="button" data-opening-cash="50000">Rp 50 rb</button>
                            <button type="button" data-opening-cash="100000">Rp 100 rb</button>
                            <button type="button" data-opening-cash="200000">Rp 200 rb</button>
                            <button type="button" data-opening-cash="500000">Rp 500 rb</button>
                        </div>
                    </div>
                    <button class="shift-open-button" type="submit"><i class="bi bi-play-circle" aria-hidden="true"></i> Buka shift &amp; mulai</button>
                </form>
            </div>
        </section>
    @endif
    <div id="connectionStatus" class="connection-status" role="status" aria-live="polite">Terhubung. Transaksi akan tersimpan langsung.</div>
    @unless ($currentShift)
        <div class="shift-lock-note" role="status">
            <i class="bi bi-info-circle" aria-hidden="true"></i>
            <span>Fitur transaksi terkunci sampai shift dibuka.</span>
        </div>
    @endunless
    <fieldset class="pos-grid" @disabled(! $currentShift)>
        <section>
            <div class="pos-card"><div class="pos-card-title">Tambah Barang</div><div class="pos-card-body"><div class="search-row"><div class="search-wrap"><input id="searchProduct" type="search" placeholder="Cari nama barang / kode / barcode..." autocomplete="off" autofocus><div class="product-results" id="productResults"></div></div><button class="btn-pos" id="searchButton" type="button">Cari</button></div><div class="customer-grid"><div><label class="field-label" for="customerType">Pelanggan</label><select id="customerType"><option>Umum</option><option>Pelanggan Member</option><option>Member VIP</option></select></div><div><label class="field-label" for="customerContact">No. Member / HP</label><input id="customerContact" type="text" placeholder="Opsional"></div></div></div></div>
            <div class="pos-card"><div class="pos-card-title d-flex justify-content-between"><span>Keranjang Belanja</span><span id="itemCount">0 Item</span></div><div class="table-wrap"><table class="cart-table"><thead><tr><th>No</th><th>Barang</th><th>Harga</th><th>Qty</th><th class="text-right">Subtotal</th><th></th></tr></thead><tbody id="cartBody"></tbody></table></div></div>
        </section>
        <aside class="pos-card">
            <div class="pos-card-title">Ringkasan Pembayaran</div>
            <div class="pos-card-body">
                <div class="checkout-summary">
                    <div class="total-box">
                        <div class="total-label">TOTAL PEMBAYARAN</div>
                        <div class="total-value" id="grandTotal">Rp 0</div>
                    </div>
                    <div class="checkout-change change-box" id="changeBox" aria-live="polite">
                        <div class="change-label" id="changeLabel">KEMBALIAN</div>
                        <div class="change-value" id="change">Rp 0</div>
                    </div>
                </div>
                <div class="payment-box">
                    <div class="payment-label">Metode Pembayaran</div>
                    <div class="payment-method" id="paymentMethod"><button type="button" class="active">Tunai</button><button type="button">QRIS</button><button type="button">Debit</button></div>
                    <label class="payment-label mt-3" id="paymentLabel" for="payment">Uang Dibayar</label>
                    <input class="payment-input" id="payment" type="number" min="0" placeholder="0">
                    <div class="payment-label mt-3" id="quickPaymentLabel">Nominal Cepat</div>
                    <div class="quick-payments" id="quickPayments"></div>
                    <p class="payment-help" id="paymentHelp" aria-live="polite">Masukkan jumlah uang yang diterima dari pelanggan.</p>
                </div>
                <div class="action-area">
                    <button type="button" class="btn-hold" id="holdButton">Tahan</button>
                    <button type="button" class="btn-cancel" id="cancelButton">Batal</button>
                    <button type="button" class="btn-pay" id="payButton">BAYAR &amp; CETAK</button>
                </div>
                <div id="receiptStatus" class="receipt-status" role="status" aria-live="polite"></div>
            </div>
        </aside>
    </fieldset>
</div>
<div id="printReceipt" class="print-receipt" aria-live="polite"></div>

<script id="product-data" type="application/json">@json($productData)</script>
<script>
const products = JSON.parse(document.getElementById('product-data').textContent);
const receiptPrintUrl = @json(route('struk.cetak'));
const offlineSyncUrl = @json(route('offline-transactions.sync'));
let cart = [], selectedPayment = 'Tunai';
const rupiah = value => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(value || 0);
const $ = id => document.getElementById(id);
const grandTotal = () => cart.reduce((sum, item) => sum + item.price * item.qty, 0);
document.querySelectorAll('[data-opening-cash]').forEach(button => button.addEventListener('click', () => {
    $('opening_cash').value = button.dataset.openingCash;
    $('opening_cash').focus();
}));
function productImage(product) { return product.image ? `<img class="product-image" src="${product.image}" alt="${product.name}">` : '<span class="product-image-placeholder" aria-hidden="true">&#9633;</span>'; }
function renderResults() { const keyword = $('searchProduct').value.toLowerCase().trim(), box = $('productResults'); if (!keyword) { box.style.display = 'none'; return; } const results = products.filter(product => [product.name, product.code, product.barcode].some(value => value.toLowerCase().includes(keyword))); box.innerHTML = results.length ? results.map(product => { const stockClass = product.stock <= 0 ? 'stock-out' : (product.stock <= 5 ? 'stock-low' : ''); const stockLabel = product.stock <= 0 ? 'Stok habis' : `Stok ${product.stock}`; return `<div class="product-item ${stockClass}" data-product-id="${product.id}" ${product.stock <= 0 ? 'aria-disabled="true"' : ''}><div class="product-info">${productImage(product)}<div><div class="product-name">${product.name}</div><div class="product-code">${product.code} - ${product.barcode} &bull; <span class="product-stock ${stockClass}">${stockLabel}</span></div></div></div><div class="product-price">${rupiah(product.price)}</div></div>`; }).join('') : '<div class="product-item">Barang tidak ditemukan</div>'; box.style.display = 'block'; }
function addToCart(id) { const product = products.find(item => item.id === id); if (!product) return; if (product.stock <= 0) { alert('Produk sedang habis.'); return; } const item = cart.find(row => row.id === id); if (item) { if (item.qty < product.stock) item.qty++; else alert('Jumlah melebihi stok yang tersedia.'); } else cart.push({ ...product, qty: 1 }); $('searchProduct').value = ''; $('productResults').style.display = 'none'; renderCart(); }
function renderCart() { const body = $('cartBody'); body.innerHTML = cart.length ? cart.map((item, index) => `<tr class="${item.stock <= 5 ? 'stock-low' : ''}"><td>${index + 1}</td><td><div class="product-info">${productImage(item)}<div><strong>${item.name}</strong><div class="product-code">${item.code}</div></div></div></td><td>${rupiah(item.price)}</td><td><div class="qty-control"><button type="button" data-action="minus" data-id="${item.id}">-</button><input type="number" min="1" max="${item.stock}" value="${item.qty}" data-action="qty" data-id="${item.id}"><button type="button" data-action="plus" data-id="${item.id}">+</button></div></td><td class="text-right"><strong>${rupiah(item.price * item.qty)}</strong></td><td><button type="button" class="remove-btn" data-action="remove" data-id="${item.id}" aria-label="Hapus ${item.name}">&times;</button></td></tr>`).join('') : '<tr><td class="empty-cart" colspan="6">Keranjang masih kosong.<br>Silakan cari atau scan barang.</td></tr>'; calculateTotal(); }
function isCashPayment() { return selectedPayment === 'Tunai'; }
function calculateTotal() { const qty = cart.reduce((sum, item) => sum + item.qty, 0); $('itemCount').textContent = `${qty} Item`; $('grandTotal').textContent = rupiah(grandTotal()); if (!isCashPayment()) $('payment').value = cart.length ? grandTotal() : ''; calculateChange(); renderQuickPayments(); }
function calculateChange() { const nonCash = !isCashPayment(); $('payment').readOnly = nonCash; $('quickPaymentLabel').hidden = nonCash; $('quickPayments').hidden = nonCash; $('paymentLabel').textContent = nonCash ? 'Nominal pembayaran' : 'Uang Dibayar'; $('paymentHelp').textContent = nonCash ? `Pastikan pembayaran ${selectedPayment} sebesar ${rupiah(grandTotal())} berhasil diterima.` : 'Masukkan jumlah uang yang diterima dari pelanggan.'; $('payButton').textContent = nonCash ? 'KONFIRMASI & CETAK' : 'BAYAR & CETAK'; const difference = nonCash ? 0 : (Number($('payment').value) || 0) - grandTotal(); $('changeBox').classList.toggle('short-payment', difference < 0); $('changeLabel').textContent = difference < 0 ? 'UANG KURANG' : 'KEMBALIAN'; $('change').textContent = rupiah(Math.abs(difference)); }
function renderQuickPayments() { $('quickPayments').innerHTML = '<button type="button" data-payment="exact">Uang Pas</button><button type="button" data-payment="10000">10rb</button><button type="button" data-payment="20000">20rb</button><button type="button" data-payment="50000">50rb</button><button type="button" data-payment="100000">100rb</button>'; }
function escapeReceiptText(value) { return String(value).replace(/[&<>"']/g, character => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[character]); }
function renderReceipt() {
    const subtotal = cart.reduce((sum, item) => sum + item.price * item.qty, 0);
    const paid = Number($('payment').value) || 0;
    const total = grandTotal();
    const receiptRows = cart.map(item => `<div class="receipt-item"><div class="receipt-item-name">${escapeReceiptText(item.name)}</div><div class="receipt-item-detail"><span>${item.qty} x ${rupiah(item.price)}</span><strong>${rupiah(item.price * item.qty)}</strong></div></div>`).join('');
    const now = new Date();
    $('printReceipt').innerHTML = `<header class="receipt-head"><h1>NesiaStore</h1><p>Jl. Transmenia No. 123 - Sabu Raijua</p><p>Telp. 0812-xxxx-xxxx</p></header><div class="receipt-meta"><div><span>No. Transaksi</span><strong>${escapeReceiptText($('transactionNumber').textContent)}</strong></div><div><span>Waktu</span><span>${escapeReceiptText(now.toLocaleString('id-ID'))}</span></div><div><span>Pelanggan</span><span>${escapeReceiptText($('customerType').value)}</span></div></div><div class="receipt-items">${receiptRows}</div><div class="receipt-totals"><div><span>Subtotal</span><span>${rupiah(subtotal)}</span></div><div class="receipt-grand"><span>TOTAL</span><span>${rupiah(total)}</span></div><div><span>${escapeReceiptText(selectedPayment)}</span><span>${rupiah(paid)}</span></div><div><span>Kembalian</span><span>${rupiah(paid - total)}</span></div></div><p class="receipt-thanks">Terima kasih atas kunjungan Anda.</p>`;
}
function receiptPayload() { return { transaction_number: $('transactionNumber').textContent, customer: $('customerType').value, payment_method: selectedPayment, paid: Number($('payment').value) || 0, items: cart.map(({ id, qty }) => ({ product_id: id, qty })) }; }
function offlineSalePayload() { return { transaction_number: $('transactionNumber').textContent, customer: $('customerType').value, payment_method: selectedPayment, paid: Number($('payment').value) || 0, sold_at: new Date().toISOString(), items: cart.map(item => ({ product_id: item.id, product_name: item.name, unit_price: item.price, qty: item.qty, stock: item.stock, offline_token: item.offline_token })) }; }
function setTransactionMeta() { const now = new Date(), suffix = Math.random().toString(36).slice(2, 6).toUpperCase(); $('transactionNumber').textContent = `TRX-${now.toISOString().slice(0, 10).replaceAll('-', '')}-${String(now.getHours() * 3600 + now.getMinutes() * 60 + now.getSeconds()).padStart(6, '0')}-${suffix}`; $('currentDate').textContent = now.toLocaleString('id-ID'); }
function resetTransaction() { cart = []; $('payment').value = ''; $('printReceipt').innerHTML = ''; renderCart(); setTransactionMeta(); }
function updateConnectionStatus() { const offline = !navigator.onLine; $('connectionStatus').classList.toggle('is-offline', offline); $('connectionStatus').textContent = offline ? 'Offline. Transaksi akan disimpan di perangkat dan disinkronkan saat internet kembali.' : 'Online. Transaksi tersimpan langsung; antrean offline akan disinkronkan otomatis.'; }
$('searchProduct').addEventListener('input', renderResults); $('searchButton').addEventListener('click', renderResults); $('searchProduct').addEventListener('keydown', event => { if (event.key === 'Enter') { const first = $('productResults').querySelector('[data-product-id]'); if (first) addToCart(Number(first.dataset.productId)); } }); $('productResults').addEventListener('click', event => { const item = event.target.closest('[data-product-id]'); if (item) addToCart(Number(item.dataset.productId)); });
$('cartBody').addEventListener('click', event => { const control = event.target.closest('[data-action]'); if (!control) return; const item = cart.find(row => row.id === Number(control.dataset.id)); if (!item) return; if (control.dataset.action === 'remove') cart = cart.filter(row => row.id !== item.id); if (control.dataset.action === 'minus') item.qty--; if (control.dataset.action === 'plus' && item.qty < item.stock) item.qty++; if (item.qty < 1) cart = cart.filter(row => row.id !== item.id); renderCart(); }); $('cartBody').addEventListener('change', event => { if (event.target.dataset.action !== 'qty') return; const item = cart.find(row => row.id === Number(event.target.dataset.id)); if (item) item.qty = Math.min(item.stock, Math.max(1, Number(event.target.value) || 1)); renderCart(); });
 $('payment').addEventListener('input', calculateChange); $('quickPayments').addEventListener('click', event => { const button = event.target.closest('[data-payment]'); if (!button) return; $('payment').value = button.dataset.payment === 'exact' ? grandTotal() : button.dataset.payment; calculateChange(); }); $('paymentMethod').addEventListener('click', event => { const button = event.target.closest('button'); if (!button) return; selectedPayment = button.textContent; document.querySelectorAll('#paymentMethod button').forEach(item => item.classList.toggle('active', item === button)); calculateTotal(); });
$('holdButton').addEventListener('click', () => alert(cart.length ? `Transaksi ${$('transactionNumber').textContent} ditahan.` : 'Tidak ada transaksi untuk ditahan.'));
$('cancelButton').addEventListener('click', () => { if (cart.length && confirm('Batalkan transaksi ini?')) resetTransaction(); });
$('payButton').addEventListener('click', () => {
    if (!cart.length) return alert('Keranjang masih kosong.');
    if (isCashPayment() && (Number($('payment').value) || 0) < grandTotal()) return alert('Uang pembayaran masih kurang.');
    if (!isCashPayment() && !confirm(`Pastikan pembayaran ${selectedPayment} sebesar ${rupiah(grandTotal())} sudah berhasil diterima. Lanjutkan transaksi?`)) return;
    const button = $('payButton');
    if (button.disabled) return;
    const status = $('receiptStatus');
    const payload = receiptPayload();
    button.disabled = true;
    button.textContent = 'MENGIRIM KE PRINTER...';
    fetch(receiptPrintUrl, { method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }, body: JSON.stringify(payload) })
        .then(async response => {
            const result = await response.json().catch(() => ({}));
            if (!response.ok) {
                const error = new Error(result.message || 'Transaksi tidak dapat dicetak.');
                error.status = response.status;
                error.transactionSaved = result.transaction_saved === true;
                throw error;
            }
            if (!result.success) {
                const error = new Error(result.message || 'Printer kasir tidak dapat mencetak struk.');
                error.status = 503;
                throw error;
            }
            return result;
        })
        .then(result => {
            payload.items.forEach(({ product_id, qty }) => {
                const product = products.find(item => item.id === product_id);
                if (product) product.stock = Math.max(0, product.stock - qty);
            });
            status.className = 'receipt-status is-visible';
            status.textContent = result.message || 'Transaksi tersimpan dan struk berhasil dicetak.';
            resetTransaction();
        })
        .catch(error => {
            status.className = 'receipt-status is-visible is-error';
            if (!error.status && (!navigator.onLine || error instanceof TypeError)) {
                const offlineSale = offlineSalePayload();
                return window.NesiaOffline.queueTransaction(offlineSale).then(async () => {
                    offlineSale.items.forEach(({ product_id, qty }) => {
                        const product = products.find(item => item.id === product_id);
                        if (product) product.stock = Math.max(0, product.stock - qty);
                    });
                    await window.NesiaOffline.saveSnapshot({
                        userId: @json(auth()->id()),
                        userName: @json(auth()->user()->name),
                        csrfToken: document.querySelector('meta[name="csrf-token"]').content,
                        products,
                        savedAt: new Date().toISOString(),
                    }).catch(() => {});
                    renderReceipt();
                    status.textContent = `Koneksi terputus. Transaksi ${offlineSale.transaction_number} tersimpan di perangkat dan menunggu sinkronisasi. Struk akan dicetak dari browser.`;
                    window.print();
                    resetTransaction();
                    if (navigator.onLine) await window.NesiaOffline.syncQueue();
                }).catch(() => {
                    status.textContent = 'Koneksi terputus dan penyimpanan lokal gagal. Jangan tutup halaman; catat transaksi secara manual.';
                });
                return;
            }
            if (error.status && error.status < 500) {
                status.textContent = `${error.message} Struk tidak dicetak; keranjang tetap tersimpan.`;
                return;
            }
            if (error.status && !error.transactionSaved) {
                status.textContent = `${error.message} Transaksi belum tersimpan; keranjang tetap tersimpan.`;
                return;
            }
            renderReceipt();
            window.print();
            status.textContent = `${error.message} Dialog cetak browser dibuka sebagai cadangan.`;
            resetTransaction();
        })
        .finally(() => { button.disabled = false; calculateChange(); });
});
document.addEventListener('keydown', event => { if (event.key === 'F2') { event.preventDefault(); $('searchProduct').focus(); } if (event.key === 'F4') { event.preventDefault(); $('payment').focus(); } if (event.key === 'Escape') $('cancelButton').click(); });
setTransactionMeta(); renderCart(); calculateChange();
window.addEventListener('DOMContentLoaded', async () => {
    try {
        await window.NesiaOffline.saveSnapshot({
            userId: @json(auth()->id()),
            userName: @json(auth()->user()->name),
            csrfToken: document.querySelector('meta[name="csrf-token"]').content,
            products,
            savedAt: new Date().toISOString(),
        });
        await window.NesiaOffline.syncQueue();
    } catch {}
    updateConnectionStatus();
});
window.addEventListener('online', updateConnectionStatus);
window.addEventListener('offline', updateConnectionStatus);
</script>
@endsection