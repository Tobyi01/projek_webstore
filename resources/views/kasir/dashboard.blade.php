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
    .cashier-dashboard { --cashier-blue: #2563d9; --cashier-text: #26374b; --cashier-muted: #8190a4; --cashier-line: #e7edf1; }
    .cashier-heading { margin-bottom: 1.5rem; }
    .cashier-heading h1 { color: #1b2b44; font-size: 1.65rem; font-weight: 750; letter-spacing: -.035em; }
    .cashier-heading p { font-size: .82rem; margin-top: .35rem; }
    .cashier-start { border-radius: .65rem; box-shadow: 0 .35rem .8rem rgba(37,99,217,.18); font-size: .76rem; font-weight: 700; padding: .72rem .95rem; }
    .cashier-stats { gap: 1rem; }
    .cashier-stat { border: 1px solid var(--cashier-line); border-radius: .85rem; box-shadow: 0 .35rem 1.2rem rgba(35,55,90,.045); text-align: left; }
    .cashier-stat header { background: #fff; color: #738198; font-size: .7rem; font-weight: 650; min-height: 3rem; padding: .85rem 1rem .4rem; }
    .cashier-stat header i { align-items: center; background: #eaf1ff; border-radius: .55rem; color: var(--cashier-blue); display: inline-flex; font-size: .9rem; height: 1.8rem; justify-content: center; width: 1.8rem; }
    .cashier-stat > strong { color: #25354e; font-size: 1.8rem; font-weight: 750; padding: .65rem 1rem .45rem; }
    .cashier-stat footer { background: #fff; border-top: 0; color: #8997aa; font-size: .66rem; min-height: 2.1rem; padding: .25rem 1rem .8rem; }
    .cashier-note { border: 1px solid var(--cashier-line); border-radius: .85rem; box-shadow: 0 .35rem 1.2rem rgba(35,55,90,.04); gap: .9rem; margin-top: 1.2rem; padding: 1rem 1.1rem; }
    .cashier-note-icon { background: #eaf1ff; flex-basis: 2.5rem; height: 2.5rem; }
    .cashier-note strong { color: #26374b; font-size: .78rem; }
    .cashier-note p { color: #8190a4; font-size: .68rem; margin-top: .25rem; }
    .cashier-note > a { color: var(--cashier-blue); font-size: .7rem; }
    @media (max-width: 480px) {
        .cashier-heading h1 { font-size: 1.4rem; }
        .cashier-stat header { font-size: .62rem; min-height: 2.8rem; padding: .65rem .7rem .3rem; }
        .cashier-stat > strong { font-size: 1.5rem; padding: .55rem .7rem .3rem; }
        .cashier-stat footer { font-size: .6rem; padding: .2rem .7rem .65rem; }
    }
</style>
@endsection