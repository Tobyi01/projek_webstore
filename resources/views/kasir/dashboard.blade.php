@extends('layouts.admin')

@section('title', 'Dashboard Kasir - NesiaStore')
@section('page-heading', 'NesiaStore · Kasir')

@section('content')
<div class="cashier-dashboard">
    <header class="cashier-heading">
        <div><h1>Dashboard</h1><p>Ringkasan barang dan aktivitas toko.</p></div>
        <a href="{{ route('kasir.index') }}" class="cashier-start"><i class="bi bi-receipt-cutoff" aria-hidden="true"></i> Mulai transaksi</a>
    </header>

    <section class="cashier-stats" aria-label="Ringkasan data toko">
        <article class="cashier-stat">
            <header><i class="bi bi-box-seam" aria-hidden="true"></i>Nama Barang</header>
            <strong>{{ number_format($cashierProductCount, 0, ',', '.') }}</strong>
            <footer>Produk aktif</footer>
        </article>
        <article class="cashier-stat">
            <header><i class="bi bi-boxes" aria-hidden="true"></i>Stok Barang</header>
            <strong>{{ number_format($cashierStockCount, 0, ',', '.') }}</strong>
            <footer>Total unit tersedia</footer>
        </article>
        <article class="cashier-stat">
            <header><i class="bi bi-bag-check" aria-hidden="true"></i>Telah Terjual</header>
            <strong>{{ number_format($cashierSoldCount, 0, ',', '.') }}</strong>
            <footer>Unit pada transaksi</footer>
        </article>
        <article class="cashier-stat">
            <header><i class="bi bi-bookmark" aria-hidden="true"></i>Kategori Barang</header>
            <strong>{{ number_format($cashierCategoryCount, 0, ',', '.') }}</strong>
            <footer>Kategori aktif</footer>
        </article>
    </section>

    <section class="cashier-note" aria-label="Status kasir">
        <span class="cashier-note-icon"><i class="bi bi-shop" aria-hidden="true"></i></span>
        <div><strong>Kasir siap digunakan</strong><p>Data stok dan produk diperbarui dari transaksi toko.</p></div>
        <a href="{{ route('kasir.index') }}">Buka kasir <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
    </section>
</div>

<style>
    .cashier-dashboard { --cashier-blue: #4a6dde; --cashier-text: #4d5669; --cashier-muted: #8993a5; --cashier-line: #e8ecf3; color: var(--cashier-text); }
    .cashier-heading { align-items: center; display: flex; justify-content: space-between; margin-bottom: 1.2rem; }
    .cashier-heading h1 { color: #51596a; font-size: 1.25rem; font-weight: 600; margin: 0; }
    .cashier-heading p { color: var(--cashier-muted); font-size: .68rem; margin: .25rem 0 0; }
    .cashier-start { align-items: center; background: var(--cashier-blue); border-radius: .25rem; color: #fff; display: inline-flex; font-size: .65rem; gap: .4rem; padding: .6rem .75rem; text-decoration: none; }
    .cashier-start:hover { background: #3659ca; color: #fff; }
    .cashier-stats { display: grid; gap: 1rem; grid-template-columns: repeat(4, minmax(0, 1fr)); }
    .cashier-stat { background: #fff; border: 1px solid #e3e8f1; border-radius: .3rem; box-shadow: 0 .25rem .65rem rgba(35,55,96,.06); min-width: 0; overflow: hidden; text-align: center; }
    .cashier-stat header { align-items: center; background: var(--cashier-blue); color: #fff; display: flex; font-size: .64rem; gap: .4rem; min-height: 2.7rem; padding: .6rem .75rem; text-align: left; }
    .cashier-stat header i { font-size: .75rem; }
    .cashier-stat > strong { color: #858d9e; display: block; font-size: 1.65rem; font-weight: 400; line-height: 1; padding: .95rem .4rem .8rem; }
    .cashier-stat footer { background: #fafbfe; border-top: 1px solid #eef1f6; color: #6681d8; font-size: .59rem; min-height: 2.25rem; padding: .65rem .5rem; }
    .cashier-note { align-items: center; background: #fff; border: 1px solid var(--cashier-line); border-left: 3px solid var(--cashier-blue); border-radius: .3rem; display: flex; gap: .7rem; margin-top: 1rem; padding: .8rem .9rem; }
    .cashier-note-icon { align-items: center; background: #edf1ff; border-radius: 50%; color: var(--cashier-blue); display: flex; flex: 0 0 2rem; height: 2rem; justify-content: center; }
    .cashier-note strong { color: #586278; display: block; font-size: .65rem; }
    .cashier-note p { color: var(--cashier-muted); font-size: .58rem; margin: .18rem 0 0; }
    .cashier-note > a { color: var(--cashier-blue); font-size: .6rem; font-weight: 700; margin-left: auto; text-decoration: none; white-space: nowrap; }
    @media (max-width: 900px) { .cashier-stats { gap: .65rem; grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (max-width: 480px) { .cashier-heading { align-items: start; flex-direction: column; gap: .7rem; }.cashier-stats { gap: .55rem; }.cashier-stat header { font-size: .56rem; min-height: 2.5rem; padding: .5rem; }.cashier-stat > strong { font-size: 1.4rem; }.cashier-note { align-items: start; flex-wrap: wrap; }.cashier-note > a { margin-left: 2.7rem; } }
    @media (prefers-reduced-motion: reduce) { .cashier-dashboard * { scroll-behavior: auto; } }
</style>
@endsection