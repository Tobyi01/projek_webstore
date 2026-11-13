<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f3f7ff">
    <title>NesiaStore — Aplikasi Kasir &amp; Manajemen Toko</title>
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=20261021-2">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <style>
        :root {
            color-scheme: light;
            --night: #f3f7ff;
            --panel: #ffffff;
            --panel-soft: #eaf3ff;
            --accent-blue: #1769dc;
            --paper: #12284a;
            --muted: #687d9d;
            --line: rgba(30, 79, 145, .14);
        }
        *, *::before, *::after { box-sizing: border-box; }
        html { min-width: 320px; scroll-behavior: smooth; }
        body { background: radial-gradient(ellipse at 82% 30%, rgba(80,153,255,.12), transparent 34%), var(--night); color: var(--paper); font-family: "Segoe UI", Arial, sans-serif; margin: 0; min-height: 100vh; }
        a { -webkit-tap-highlight-color: transparent; }
        .page { margin: 0 auto; max-width: 1440px; overflow: hidden; padding: 0 6.1%; }
        .topbar { align-items: center; animation: enter .65s ease both; border-bottom: 1px solid var(--line); display: flex; justify-content: space-between; min-height: 82px; }
        .brand { align-items: center; color: var(--paper); display: inline-flex; gap: .75rem; text-decoration: none; }
        .brand-icon { align-items: center; background: var(--accent-blue); border-radius: 12px; color: #fff; display: flex; font-size: 1.2rem; height: 40px; justify-content: center; width: 40px; }
        .brand-name { font-size: .96rem; font-weight: 750; letter-spacing: -.03em; }
        .brand-name small { color: var(--muted); display: block; font-size: .61rem; font-weight: 500; letter-spacing: .04em; margin-top: 3px; }
        .top-links { align-items: center; display: flex; gap: 1.5rem; }
        .top-login { color: var(--paper); font-size: .8rem; font-weight: 650; text-decoration: none; transition: color .2s ease; }
        .top-login:hover { color: var(--accent-blue); }
        .top-signup { background: var(--accent-blue); border-radius: 7px; color: #fff; font-size: .78rem; font-weight: 750; padding: .76rem 1rem; text-decoration: none; transition: background .2s ease, transform .2s ease; }
        .top-signup:hover, .cta:hover { background: #0e4fae; transform: translateY(-2px); }
        .hero { align-items: center; display: grid; gap: 7%; grid-template-columns: minmax(0, .9fr) minmax(0, 1.1fr); min-height: 680px; padding: 4.3rem 0 4.8rem; }
        .hero-copy { animation: enter .75s .1s ease both; max-width: 510px; }
        .eyebrow { align-items: center; color: var(--accent-blue); display: inline-flex; font-size: .62rem; font-weight: 750; gap: .55rem; letter-spacing: .12em; text-transform: uppercase; }
        .eyebrow::before { background: var(--accent-blue); border-radius: 50%; content: ""; height: 7px; width: 7px; }
        h1 { font-size: clamp(2.6rem, 5vw, 4.8rem); font-weight: 700; letter-spacing: -.075em; line-height: .95; margin: 1.2rem 0 1rem; }
        h1 span { color: var(--accent-blue); display: block; font-family: Georgia, "Times New Roman", serif; font-weight: 400; font-style: italic; letter-spacing: -.065em; }
        .intro { color: #5c6f8d; font-size: .95rem; line-height: 1.7; margin: 0; max-width: 400px; }
        .hero-actions { align-items: center; display: flex; flex-wrap: wrap; gap: 1.2rem; margin-top: 2rem; }
        .cta { align-items: center; background: var(--accent-blue); border-radius: 7px; box-shadow: 0 8px 20px rgba(23,105,220,.2); color: #fff; display: inline-flex; font-size: .82rem; font-weight: 750; gap: .8rem; padding: .95rem 1.15rem; text-decoration: none; transition: background .2s ease, transform .2s ease; }
        .cta i { font-size: .92rem; }
        .text-link { color: #31527f; font-size: .8rem; font-weight: 650; text-decoration: none; transition: color .2s ease; }
        .text-link:hover { color: var(--accent-blue); }
        .hero-note { align-items: center; color: #687d9d; display: flex; font-size: .7rem; gap: .5rem; margin-top: 2.2rem; }
        .hero-note i { color: var(--accent-blue); }
        .dashboard-wrap { animation: enter-right .8s .18s ease both; min-width: 0; perspective: 1200px; position: relative; }
        .dashboard { background: #e9eff8; border: 1px solid rgba(255,255,255,.85); border-radius: 15px; box-shadow: 0 28px 70px rgba(35,75,130,.18); color: #12284a; overflow: hidden; padding: 1.15rem; position: relative; transform: rotateY(-5deg) rotateX(2deg); transform-style: preserve-3d; transition: transform .5s ease; }
        .dashboard-wrap:hover .dashboard { transform: rotateY(0) rotateX(0) translateY(-5px); }
        .dash-top { align-items: center; display: flex; justify-content: space-between; padding: .15rem .1rem 1rem; }
        .dash-brand { align-items: center; display: flex; font-size: .79rem; font-weight: 800; gap: .55rem; }
        .dash-logo { align-items: center; background: #1769dc; border-radius: 7px; color: #fff; display: flex; font-size: .86rem; height: 29px; justify-content: center; width: 29px; }
        .dash-brand small { color: #7d877d; display: block; font-size: .53rem; font-weight: 600; letter-spacing: .1em; margin-top: 2px; }
        .dash-day { color: #667166; font-size: .62rem; }
        .dash-content { display: grid; gap: .8rem; grid-template-columns: 1.2fr .8fr; }
        .sales-card, .stock-card { background: #fff; border: 1px solid #e4e7de; border-radius: 10px; }
        .sales-card { min-height: 270px; padding: 1.1rem; }
        .card-head { align-items: flex-start; display: flex; justify-content: space-between; }
        .card-label { color: #687368; font-size: .61rem; font-weight: 650; }
        .sales-total { color: #12284a; display: block; font-size: 1.65rem; font-weight: 750; letter-spacing: -.06em; margin-top: .42rem; }
        .sales-change { background: #e5f1ff; border-radius: 20px; color: #1769a8; font-size: .54rem; font-weight: 750; padding: .35rem .5rem; }
        .chart { align-items: end; border-bottom: 1px solid #e8ebe4; display: flex; gap: 5%; height: 120px; justify-content: space-between; margin-top: 1.15rem; padding: 0 .15rem; position: relative; }
        .chart::before, .chart::after { border-top: 1px dashed #e6e9e1; content: ""; left: 0; position: absolute; right: 0; }
        .chart::before { top: 33%; }.chart::after { top: 66%; }
        .bar { animation: grow .9s cubic-bezier(.2,.8,.2,1) both; background: #dfe4d8; border-radius: 4px 4px 0 0; flex: 1; min-width: 10px; position: relative; z-index: 1; }
        .bar:nth-child(1) { height: 35%; animation-delay: .35s; }.bar:nth-child(2) { height: 54%; animation-delay: .4s; }.bar:nth-child(3) { height: 42%; animation-delay: .45s; }.bar:nth-child(4) { height: 76%; animation-delay: .5s; }.bar:nth-child(5) { height: 60%; animation-delay: .55s; }.bar:nth-child(6) { height: 91%; animation-delay: .6s; background: #1769dc; }
        .chart-labels { color: #929b90; display: flex; font-size: .53rem; justify-content: space-between; padding-top: .45rem; }
        .stock-card { display: flex; flex-direction: column; justify-content: space-between; padding: 1rem; }
        .stock-title { color: #687368; font-size: .61rem; font-weight: 650; }
        .stock-visual { align-items: center; align-self: center; background: conic-gradient(#1769dc 0 73%, #dfe9f6 73% 100%); border-radius: 50%; display: flex; height: 104px; justify-content: center; position: relative; width: 104px; }
        .stock-visual::before { background: #fff; border-radius: 50%; content: ""; height: 77px; position: absolute; width: 77px; }
        .stock-number { color: #12284a; font-size: 1.16rem; font-weight: 800; position: relative; }
        .stock-number small { color: #8a9488; display: block; font-size: .48rem; font-weight: 600; text-align: center; }
        .stock-foot { align-items: center; border-top: 1px solid #edf0e9; color: #687368; display: flex; font-size: .57rem; justify-content: space-between; padding-top: .7rem; }
        .stock-foot strong { color: #12284a; }
        .activity-card { align-items: center; background: #173c70; border-radius: 10px; color: #fff; display: flex; gap: .8rem; grid-column: 1 / -1; padding: .9rem 1rem; }
        .activity-icon { align-items: center; background: rgba(255,255,255,.12); border-radius: 8px; color: #b9dcff; display: flex; flex: 0 0 36px; height: 36px; justify-content: center; }
        .activity-copy { flex: 1; }.activity-copy strong { display: block; font-size: .65rem; }.activity-copy small { color: #c3d4e9; display: block; font-size: .55rem; margin-top: 3px; }
        .activity-value { color: #c5e1ff; font-size: .68rem; font-weight: 750; }
        .orbit { border: 1px solid rgba(23,105,220,.2); border-radius: 50%; height: 125%; left: -9%; pointer-events: none; position: absolute; top: -12%; transform: rotate(-24deg); width: 118%; z-index: -1; }
        .orbit::after { background: var(--accent-blue); border-radius: 50%; box-shadow: 0 0 24px rgba(23,105,220,.4); content: ""; height: 8px; left: 15%; position: absolute; top: 18%; width: 8px; }
        .about { border-top: 1px solid var(--line); padding: 3.5rem 0 3.8rem; }
        .about-heading { margin-bottom: 1.7rem; max-width: 620px; }
        .about-heading h2 { color: var(--paper); font-size: clamp(1.7rem, 3.5vw, 2.5rem); letter-spacing: -.055em; line-height: 1.12; margin: .65rem 0; }
        .about-heading p { color: var(--muted); font-size: .9rem; line-height: 1.75; margin: 0; }
        .about-grid { display: grid; gap: .9rem; grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .about-card { background: rgba(255,255,255,.82); border: 1px solid var(--line); border-radius: 12px; padding: 1.2rem; }
        .about-card i { align-items: center; background: var(--panel-soft); border-radius: 9px; color: var(--accent-blue); display: inline-flex; height: 2.3rem; justify-content: center; width: 2.3rem; }
        .about-card h3 { color: var(--paper); font-size: .9rem; margin: .8rem 0 .4rem; }
        .about-card p { color: var(--muted); font-size: .75rem; line-height: 1.7; margin: 0; }
        .about-note { color: var(--muted); font-size: .72rem; line-height: 1.7; margin: 1.1rem 0 0; }
        .about-note strong { color: var(--paper); }
        .section-bottom { align-items: center; border-top: 1px solid var(--line); display: flex; justify-content: space-between; padding: 1.25rem 0 2rem; }
        .bottom-label { color: #687d9d; font-size: .63rem; letter-spacing: .12em; text-transform: uppercase; }
        .benefits { display: flex; flex-wrap: wrap; gap: 1.6rem; }
        .benefit { align-items: center; color: #536985; display: flex; font-size: .69rem; gap: .5rem; }
        .benefit i { color: var(--accent-blue); }
        .footer { color: #687d9d; font-size: .62rem; padding: 0 0 1.5rem; }
        .footer span { color: var(--accent-blue); }
        @keyframes enter { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes enter-right { from { opacity: 0; transform: translateX(24px); } to { opacity: 1; transform: translateX(0); } }
        @keyframes grow { from { transform: scaleY(.1); transform-origin: bottom; } to { transform: scaleY(1); transform-origin: bottom; } }
        @media (max-width: 900px) {
            .page { padding: 0 5%; }
            .hero { gap: 5%; grid-template-columns: minmax(0, .85fr) minmax(0, 1.15fr); min-height: 610px; }
            h1 { font-size: clamp(3.1rem, 7vw, 4.7rem); }
            .sales-card { min-height: 250px; padding: .85rem; }
            .stock-card { padding: .8rem; }
            .stock-visual { height: 88px; width: 88px; }.stock-visual::before { height: 65px; width: 65px; }
            .chart { height: 105px; }
        }
        @media (max-width: 680px) {
            .page { padding: 0 1.15rem; }
            .topbar { min-height: 70px; }
            .top-links { gap: .8rem; }.top-login { font-size: .75rem; }.top-signup { font-size: .72rem; padding: .68rem .8rem; }
            .hero { gap: 3rem; grid-template-columns: 1fr; min-height: auto; padding: 4rem 0 3.6rem; }
            .hero-copy { max-width: 540px; }.hero-copy h1 { font-size: clamp(3.5rem, 14vw, 5.4rem); }
            .intro { font-size: .94rem; max-width: 380px; }
            .dashboard-wrap { margin: 0 auto; max-width: 540px; width: 100%; }
            .dashboard { padding: .8rem; transform: none; }
            .dashboard-wrap:hover .dashboard { transform: translateY(-3px); }
            .sales-card { min-height: 235px; }.chart { height: 98px; }
            .section-bottom { align-items: flex-start; flex-direction: column; gap: 1rem; padding-top: 1.1rem; }
            .benefits { gap: .8rem 1.2rem; }
            .about { padding: 2.8rem 0; }
            .about-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 390px) {
            .page { padding: 0 1rem; }.brand-name { font-size: .88rem; }.top-links { gap: .55rem; }
            .top-signup { padding: .65rem .65rem; }.hero { padding-top: 3rem; }
            .dash-content { gap: .5rem; grid-template-columns: 1.1fr .9fr; }
            .sales-card { min-height: 215px; padding: .7rem; }.stock-card { padding: .65rem; }
            .sales-total { font-size: 1.4rem; }.stock-visual { height: 76px; width: 76px; }.stock-visual::before { height: 56px; width: 56px; }
            .activity-card { padding: .75rem; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: .01ms !important; animation-iteration-count: 1 !important; scroll-behavior: auto !important; transition-duration: .01ms !important; }
        }
        body {
            background:
                radial-gradient(ellipse at 86% 8%, rgba(32, 132, 255, .30), transparent 28%),
                radial-gradient(ellipse at 14% 42%, rgba(45, 205, 235, .25), transparent 32%),
                radial-gradient(ellipse at 76% 74%, rgba(115, 139, 255, .16), transparent 34%),
                #eef6ff;
        }
        .page { max-width: 1180px; padding: 0 4.4%; }
        .topbar {
            background: linear-gradient(110deg, rgba(220, 244, 255, .88), rgba(255, 255, 255, .82) 52%, rgba(237, 244, 255, .9));
            border: 1px solid rgba(255, 255, 255, .8);
            border-radius: 13px;
            box-shadow: 0 12px 32px rgba(48, 101, 163, .09);
            margin-top: 7px;
            min-height: 54px;
            padding: 0 14px;
        }
        .brand { gap: .55rem; }
        .brand-icon { border-radius: 9px; height: 31px; width: 31px; }
        .brand-name { font-size: .9rem; font-weight: 650; }
        .brand-name small { display: none; }
        .top-login {
            background: rgba(255, 255, 255, .8);
            border: 1px solid rgba(24, 58, 103, .08);
            border-radius: 10px;
            box-shadow: 0 3px 8px rgba(27, 55, 96, .08);
            color: #10213c;
            padding: .65rem 1rem;
        }
        .hero {
            gap: 5%;
            grid-template-columns: minmax(0, .92fr) minmax(0, 1.08fr);
            min-height: 420px;
            padding: 3.25rem 1rem 2.4rem;
        }
        .hero-copy { max-width: 430px; }
        .eyebrow {
            background: rgba(198, 226, 255, .76);
            border-radius: 999px;
            color: #133050;
            font-size: .72rem;
            gap: .45rem;
            letter-spacing: 0;
            padding: .42rem .72rem;
            text-transform: none;
        }
        .eyebrow::before { display: none; }
        .hero-copy > .eyebrow::after {
            content: "✦";
            font-size: .9rem;
            order: -1;
        }
        h1 {
            font-family: "Segoe UI", Arial, sans-serif;
            font-size: clamp(3.2rem, 5.8vw, 4.15rem);
            font-weight: 800;
            letter-spacing: -.075em;
            line-height: 1.02;
            margin: 1rem 0 .85rem;
        }
        h1 span {
            background: linear-gradient(100deg, #176bdb 5%, #7862d6 58%, #22a8ac 100%);
            background-clip: text;
            color: transparent;
            display: block;
            font-family: inherit;
            font-style: normal;
            font-weight: 800;
            letter-spacing: -.075em;
            -webkit-background-clip: text;
        }
        .intro { color: #25364e; font-size: .78rem; line-height: 1.6; max-width: 360px; }
        .hero-actions { gap: .65rem; margin-top: 1.1rem; }
        .cta {
            background: #102d54;
            border-radius: 9px;
            box-shadow: 0 5px 12px rgba(16, 45, 84, .2);
            font-size: .75rem;
            gap: .55rem;
            padding: .72rem .9rem;
        }
        .cta:hover { background: #1b477f; }
        .text-link {
            align-items: center;
            border: 1px solid rgba(25, 50, 84, .28);
            border-radius: 9px;
            color: #263c59;
            display: inline-flex;
            gap: .45rem;
            padding: .68rem .82rem;
        }
        .text-link::after { content: "▷"; font-size: .72rem; }
        .hero-note {
            color: #34445a;
            flex-wrap: wrap;
            font-size: .68rem;
            gap: .45rem 1rem;
            margin-top: 1.25rem;
        }
        .hero-note i { color: #132d50; }
        .dashboard-wrap { z-index: 0; }
        .dashboard-wrap::before {
            background: radial-gradient(circle, rgba(25, 175, 232, .7), rgba(39, 114, 230, .25) 48%, transparent 72%);
            border-radius: 50%;
            content: "";
            height: 220px;
            position: absolute;
            right: -7%;
            top: -10%;
            width: 220px;
            z-index: -1;
        }
        .dashboard-wrap::after {
            background: linear-gradient(145deg, #21c0df, #2859d7 70%);
            border-radius: 50%;
            box-shadow: 0 8px 20px rgba(22, 89, 205, .28);
            content: "";
            height: 36px;
            position: absolute;
            right: 24%;
            top: -13%;
            width: 36px;
            z-index: 1;
        }
        .dashboard {
            background: rgba(239, 246, 255, .94);
            border: 1px solid rgba(255, 255, 255, .9);
            border-radius: 15px;
            box-shadow: 0 24px 54px rgba(32, 78, 145, .2);
            padding: .9rem;
        }
        .sales-card, .stock-card { border-color: #e5ecf5; }
        .bar { background: #36b5b2; }
        .bar:nth-child(odd) { background: #1673dc; }
        .bar:nth-child(6) { background: #1673dc; }
        .about { border-top-color: rgba(30, 79, 145, .12); }
        @media (max-width: 900px) {
            .hero { min-height: 390px; padding-left: .4rem; padding-right: .4rem; }
            h1 { font-size: clamp(3rem, 6.2vw, 3.7rem); }
        }
        @media (max-width: 680px) {
            .hero { min-height: auto; padding: 3rem 0 3.6rem; }
        }
    </style>
</head>
<body>
    <main class="page">
        <nav class="topbar" aria-label="Navigasi utama">
            <a class="brand" href="{{ url('/') }}" aria-label="NesiaStore beranda">
                <span class="brand-icon"><i class="bi bi-shop-window" aria-hidden="true"></i></span>
                <span class="brand-name">NesiaStore</span>
            </a>
            <div class="top-links">
                <a class="top-login" href="{{ route('login') }}">Masuk</a>
            </div>
        </nav>
        <section class="hero" aria-labelledby="hero-title">
            <div class="hero-copy">
                <span class="eyebrow">Platform Kasir &amp; Manajemen Toko Next-Gen</span>
                <h1 id="hero-title">Kelola toko.<span>Dari satu tempat.</span></h1>
                <p class="intro">NesiaStore adalah aplikasi berbasis web untuk mencatat penjualan, mengelola produk dan stok, serta melihat riwayat transaksi toko dalam satu sistem.</p>
                <div class="hero-actions">
                    <a class="cta" href="{{ route('login') }}">Masuk ke aplikasi <i class="bi bi-arrow-up-right" aria-hidden="true"></i></a>
                    <a class="text-link" href="#tentang-nesiastore">Kenali fitur</a>
                </div>
                <div class="hero-note">
                    <span><i class="bi bi-check2" aria-hidden="true"></i> Gratis Coba</span>
                    <span><i class="bi bi-check2" aria-hidden="true"></i> Laporan Real-time</span>
                    <span><i class="bi bi-check2" aria-hidden="true"></i> Kelola stok</span>
                </div>
            </div>
            <div class="dashboard-wrap" aria-label="Contoh tampilan ringkasan dashboard toko">
                <span class="orbit" aria-hidden="true"></span>
                <div class="dashboard">
                    <div class="dash-top">
                        <div class="dash-brand"><span class="dash-logo"><i class="bi bi-shop" aria-hidden="true"></i></span><span>NesiaStore</span></div>
                        <span class="dash-day">Hari ini <i class="bi bi-chevron-down" aria-hidden="true"></i></span>
                    </div>
                    <div class="dash-content">
                        <section class="sales-card" aria-label="Ilustrasi penjualan">
                            <div class="card-head"><div><span class="card-label">Total penjualan</span><strong class="sales-total">Rp 4.820.000</strong></div><span class="sales-change"><i class="bi bi-arrow-up-right" aria-hidden="true"></i> 12,8%</span></div>
                            <div class="chart" aria-hidden="true"><span class="bar"></span><span class="bar"></span><span class="bar"></span><span class="bar"></span><span class="bar"></span><span class="bar"></span></div>
                            <div class="chart-labels"><span>08.00</span><span>10.00</span><span>12.00</span><span>14.00</span><span>16.00</span><span>18.00</span></div>
                        </section>
                        <section class="stock-card" aria-label="Ilustrasi stok produk">
                            <span class="stock-title">Stok produk</span>
                            <div class="stock-visual"><span class="stock-number">73%<small>TERSEDIA</small></span></div>
                            <div class="stock-foot"><span>Perlu restok</span><strong>08 item</strong></div>
                        </section>
                        <div class="activity-card">
                            <span class="activity-icon"><i class="bi bi-receipt-cutoff" aria-hidden="true"></i></span>
                            <span class="activity-copy"><strong>Transaksi tercatat</strong><small>Penjualan hari ini berjalan lancar</small></span>
                            <span class="activity-value">+ 24 transaksi</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="about" id="tentang-nesiastore" aria-labelledby="about-title">
            <div class="about-heading">
                <span class="eyebrow">Semua kebutuhan toko, satu aplikasi</span>
                <h2 id="about-title">Apa yang bisa dilakukan dengan NesiaStore?</h2>
                <p>Gunakan NesiaStore melalui browser untuk menjalankan pekerjaan toko sehari-hari tanpa berpindah-pindah aplikasi.</p>
            </div>
            <div class="about-grid">
                <article class="about-card">
                    <i class="bi bi-cart-check" aria-hidden="true"></i>
                    <h3>Proses transaksi penjualan</h3>
                    <p>Cari atau scan produk, masukkan barang ke keranjang, pilih metode pembayaran, lalu simpan transaksi dan cetak struk.</p>
                </article>
                <article class="about-card">
                    <i class="bi bi-box-seam" aria-hidden="true"></i>
                    <h3>Kelola produk dan stok</h3>
                    <p>Catat informasi produk, harga, dan jumlah stok. Status stok menipis atau habis membantu menentukan barang yang perlu diperiksa.</p>
                </article>
                <article class="about-card">
                    <i class="bi bi-clipboard-data" aria-hidden="true"></i>
                    <h3>Pantau riwayat dan aktivitas</h3>
                    <p>Lihat ringkasan penjualan dan telusuri riwayat transaksi berdasarkan periode. Admin juga dapat mengelola akun kasir.</p>
                </article>
            </div>
            <p class="about-note"><strong>Untuk pemilik atau admin:</strong> kelola produk, stok, akun kasir, dan pantau laporan. <strong>Untuk kasir:</strong> proses penjualan dan lihat transaksi yang dibuat sendiri.</p>
        </section>
        <section class="section-bottom" aria-label="Keunggulan NesiaStore">
            <span class="bottom-label">Satu workspace, banyak beres</span>
            <div class="benefits">
                <span class="benefit"><i class="bi bi-lightning-charge-fill" aria-hidden="true"></i>Kasir praktis</span>
                <span class="benefit"><i class="bi bi-box-seam-fill" aria-hidden="true"></i>Stok terpantau</span>
                <span class="benefit"><i class="bi bi-bar-chart-fill" aria-hidden="true"></i>Penjualan tercatat</span>
            </div>
        </section>
        <footer class="footer">Dirancang untuk bantu toko <span>terus bertumbuh.</span></footer>
    </main>
    <script src="{{ asset('js/pwa.js') }}?v=20261109-1" defer></script>
</body>
</html>
