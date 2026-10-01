<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NesiaStore - Retail Workspace</title>
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <style>
        :root { --ink: #12284a; --blue: #1769dc; --blue-dark: #0e4fae; --sky: #eaf3ff; --cyan: #c9f4fb; --muted: #687d9d; --line: #dce7f5; }
        * { box-sizing: border-box; }
        html, body { margin: 0; min-height: 100%; }
        body { background: #fff; color: var(--ink); font-family: "Trebuchet MS", "Segoe UI", sans-serif; }
        a { -webkit-tap-highlight-color: transparent; }
        .splash { background: #fff; min-height: 100vh; overflow: hidden; }
        .hero-band { background-color: #f3f7ff; background-image: linear-gradient(rgba(44,105,190,.035) 1px, transparent 1px), linear-gradient(90deg, rgba(44,105,190,.035) 1px, transparent 1px); background-size: 34px 34px; }
        .site-nav, .hero-grid, .feature-strip { margin: 0 auto; max-width: 76rem; width: calc(100% - 3rem); }
        .site-nav { align-items: center; animation: welcome-in .55s ease both; border-bottom: 1px solid rgba(30,79,145,.11); display: flex; justify-content: space-between; padding: 1.15rem 0; }
        .brand-mark { align-items: center; color: var(--ink); display: flex; gap: .65rem; text-decoration: none; }
        .mark { background: #fff; border: 1px solid var(--line); border-radius: .65rem; height: 2.8rem; object-fit: contain; padding: .45rem; width: 2.8rem; }
        .brand-mark strong { display: block; font-size: 1rem; }.brand-mark small { color: var(--muted); display: block; font-size: .62rem; margin-top: .15rem; }
        .auth-links { align-items: center; display: flex; gap: 1rem; }
        .auth-button { border-radius: .45rem; color: var(--ink); font-size: .75rem; font-weight: 700; padding: .7rem .95rem; text-decoration: none; transition: background .2s, color .2s, transform .2s; }
        .auth-button:hover { background: #e2eeff; color: var(--blue-dark); transform: translateY(-2px); }
        .register-button { background: var(--blue); box-shadow: 0 .35rem .8rem rgba(23,105,220,.18); color: #fff; }.register-button:hover { background: var(--blue-dark); color: #fff; }
        .hero-grid { align-items: center; display: grid; gap: 3.5rem; grid-template-columns: 1fr 1.05fr; padding: 4.1rem 0 4.4rem; }
        .content { animation: welcome-in .7s .08s ease both; max-width: 34rem; }
        .eyebrow { align-items: center; background: #e2efff; border: 1px solid #d2e5ff; border-radius: 2rem; color: var(--blue-dark); display: inline-flex; font-size: .65rem; font-weight: 800; gap: .5rem; padding: .5rem .75rem; text-transform: uppercase; }
        .eyebrow i { color: var(--blue); font-size: .8rem; }
        h1 { font-size: 3.65rem; font-weight: 800; line-height: 1.04; margin: 1.35rem 0 1rem; max-width: 35rem; }
        h1 span { color: var(--blue); }
        .content p { color: #5c6f8d; font-size: .98rem; line-height: 1.75; margin: 0; max-width: 28rem; }
        .welcome-actions { align-items: center; display: flex; flex-wrap: wrap; gap: .8rem; margin-top: 1.65rem; }
        .welcome-action { align-items: center; border-radius: .5rem; display: inline-flex; font-size: .78rem; font-weight: 800; gap: .7rem; padding: .9rem 1.1rem; text-decoration: none; transition: box-shadow .2s, transform .2s, background .2s; }
        .welcome-action:hover { box-shadow: 0 .6rem 1.2rem rgba(23,65,125,.16); transform: translateY(-2px); }
        .welcome-primary { background: var(--blue); box-shadow: 0 .45rem 1rem rgba(23,105,220,.2); color: #fff; }.welcome-primary:hover { background: var(--blue-dark); color: #fff; }
        .welcome-secondary { color: #31527f; }.welcome-secondary:hover { background: #e5effd; color: var(--blue-dark); }
        .welcome-action i { font-size: .95rem; }
        .hero-proof { border-top: 1px solid #d9e4f4; display: flex; flex-wrap: wrap; gap: 1.1rem; margin-top: 2rem; padding-top: 1.1rem; }
        .hero-proof span { align-items: center; color: #536985; display: flex; font-size: .67rem; font-weight: 700; gap: .4rem; }.hero-proof i { color: #2380d8; font-size: .85rem; }
        .pos-scene { animation: scene-in .8s .16s ease both; min-height: 27rem; position: relative; }
        .pos-window { background: #fff; border: 1px solid #d7e4f5; border-radius: 1rem; box-shadow: 0 1.7rem 3.8rem rgba(28,71,132,.16); padding: .8rem; position: absolute; right: 0; top: .25rem; transform: rotate(1deg); width: min(100%, 32rem); }
        .pos-toolbar { align-items: center; display: flex; justify-content: space-between; padding: .45rem .45rem .85rem; }
        .mini-brand { align-items: center; display: flex; gap: .55rem; }.mini-brand > span:last-child { color: var(--ink); font-size: .68rem; font-weight: 800; }.mini-brand small { color: #8193ad; display: block; font-size: .48rem; letter-spacing: .08em; margin-top: .12rem; }
        .mini-mark { align-items: center; background: var(--blue); border-radius: .45rem; color: #fff; display: flex; font-size: .95rem; height: 2rem; justify-content: center; width: 2rem; }
        .pos-indicator { align-items: center; background: #edf8f2; border-radius: 2rem; color: #39815b; display: flex; font-size: .52rem; font-weight: 800; gap: .35rem; padding: .4rem .55rem; }.pos-indicator i { background: #42b779; border-radius: 50%; height: .4rem; width: .4rem; }
        .pos-content { background: #f5f8fd; border: 1px solid #eaf0f8; border-radius: .7rem; display: grid; gap: .7rem; grid-template-columns: 1.05fr .95fr; min-height: 20rem; padding: .7rem; }
        .terminal-panel { align-items: center; background: #1769dc; border-radius: .6rem; color: #fff; display: flex; flex-direction: column; justify-content: center; min-height: 18.5rem; overflow: hidden; padding: 1rem; position: relative; text-align: center; }
        .terminal-panel::before { border: 1px solid rgba(255,255,255,.13); border-radius: 50%; content: ""; height: 15rem; position: absolute; right: -8rem; top: -8rem; width: 15rem; }
        .terminal-label { color: #c4e4ff; font-size: .53rem; font-weight: 800; letter-spacing: .12em; position: absolute; top: 1rem; }
        .terminal-icon { align-items: center; background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.28); border-radius: 1.2rem; box-shadow: 0 .8rem 1.6rem rgba(0,39,107,.18); display: flex; height: 6.4rem; justify-content: center; margin-bottom: .8rem; position: relative; width: 6.4rem; }
        .terminal-icon i { color: #fff; font-size: 3.2rem; }
        .terminal-panel strong { font-size: 1rem; position: relative; }.terminal-panel small { color: #d3e8ff; font-size: .62rem; margin-top: .25rem; position: relative; }
        .receipt-panel { background: #fff; border: 1px solid #e3ebf5; border-radius: .6rem; padding: .85rem; }
        .receipt-heading { align-items: center; color: var(--ink); display: flex; font-size: .68rem; font-weight: 800; justify-content: space-between; margin-bottom: .7rem; }.receipt-heading i { color: #91a3bb; }
        .receipt-row { align-items: center; border-bottom: 1px solid #edf1f6; display: flex; gap: .55rem; min-height: 3.3rem; }.receipt-row > i:first-child { align-items: center; background: #edf4ff; border-radius: .4rem; color: var(--blue); display: flex; font-size: .75rem; height: 1.8rem; justify-content: center; width: 1.8rem; }.receipt-row > i:last-child { color: #a6b3c5; font-size: .58rem; margin-left: auto; }.receipt-row strong, .receipt-row small { display: block; }.receipt-row strong { color: #294466; font-size: .58rem; }.receipt-row small { color: #8999ae; font-size: .5rem; margin-top: .15rem; }
        .receipt-ready { align-items: center; background: #ecf7ff; border-radius: .45rem; color: #2865a9; display: flex; font-size: .54rem; font-weight: 800; gap: .4rem; margin-top: .75rem; padding: .6rem; }.receipt-ready i { color: #2785d1; font-size: .75rem; }
        .floating-receipt { align-items: center; animation: float-soft 4s ease-in-out infinite; background: #fff; border: 1px solid #dfe9f6; border-radius: .7rem; bottom: .2rem; box-shadow: 0 .8rem 1.8rem rgba(28,71,132,.14); display: flex; gap: .6rem; left: -.7rem; padding: .75rem .9rem; position: absolute; }
        .floating-receipt > span:nth-child(2) strong, .floating-receipt > span:nth-child(2) small { display: block; }.floating-receipt strong { color: #23466f; font-size: .62rem; }.floating-receipt small { color: #8798ae; font-size: .52rem; margin-top: .18rem; }.floating-receipt > i { color: #35a676; font-size: .88rem; margin-left: .4rem; }
        .receipt-icon { align-items: center; background: #e7f2ff; border-radius: .5rem; color: var(--blue); display: flex; font-size: 1rem; height: 2.2rem; justify-content: center; width: 2.2rem; }
        .feature-strip { align-items: stretch; display: grid; grid-template-columns: repeat(3, 1fr); padding: 1.6rem 0 2rem; }
        .feature { align-items: center; border-right: 1px solid var(--line); display: flex; gap: .8rem; padding: .3rem 1.4rem; }.feature:first-child { padding-left: 0; }.feature:last-child { border: 0; }
        .feature-icon { align-items: center; background: #eaf3ff; border-radius: .6rem; color: var(--blue); display: flex; flex: 0 0 2.5rem; font-size: 1rem; height: 2.5rem; justify-content: center; }
        .feature strong, .feature span { display: block; }.feature strong { color: #18345c; font-size: .72rem; }.feature span { color: var(--muted); font-size: .6rem; line-height: 1.5; margin-top: .2rem; }
        @keyframes welcome-in { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes scene-in { from { opacity: 0; transform: translateX(18px); } to { opacity: 1; transform: translateX(0); } }
        @keyframes float-soft { 0%, 100% { translate: 0 0; } 50% { translate: 0 -5px; } }
        @media (max-width: 900px) { .hero-grid { gap: 2rem; grid-template-columns: 1fr 1fr; }.pos-scene { min-height: 24rem; }.pos-content { min-height: 18rem; }.terminal-panel { min-height: 16.5rem; }.feature { padding-left: .9rem; padding-right: .9rem; } }
        @media (max-width: 650px) { .site-nav, .hero-grid, .feature-strip { width: calc(100% - 2rem); }.site-nav { padding: .85rem 0; }.auth-links { gap: .2rem; }.auth-button { font-size: .68rem; padding: .6rem .7rem; }.mark { height: 2.4rem; width: 2.4rem; }.brand-mark strong { font-size: .9rem; }.hero-grid { gap: 1.2rem; grid-template-columns: 1fr; padding: 2.5rem 0 2rem; }.content { max-width: none; }.content h1 { font-size: 2.75rem; }.content p { font-size: .9rem; }.hero-proof { gap: .65rem 1rem; }.hero-proof span { font-size: .62rem; }.pos-scene { margin: .3rem auto 0; min-height: 22rem; width: min(100%, 30rem); }.pos-window { width: calc(100% - .25rem); }.pos-content { min-height: 17rem; }.terminal-panel { min-height: 15.5rem; }.terminal-icon { height: 5.2rem; width: 5.2rem; }.terminal-icon i { font-size: 2.6rem; }.floating-receipt { bottom: -.1rem; left: -.15rem; }.feature-strip { gap: 0; grid-template-columns: 1fr; padding: .4rem 0 1.2rem; }.feature, .feature:first-child { border-bottom: 1px solid var(--line); border-right: 0; padding: .85rem 0; }.feature:last-child { border-bottom: 0; } }
        @media (max-width: 380px) { .brand-mark small { display: none; }.auth-button { font-size: .63rem; padding: .55rem .55rem; }.content h1 { font-size: 2.45rem; }.pos-content { gap: .45rem; padding: .45rem; }.receipt-panel { padding: .65rem; }.floating-receipt { padding: .6rem; } }
        @media (prefers-reduced-motion: reduce) { *, *::before, *::after { animation-duration: .01ms !important; animation-iteration-count: 1 !important; scroll-behavior: auto !important; transition-duration: .01ms !important; } }
    </style>
</head>
<body>
    <main class="splash" aria-label="NesiaStore">
        <section class="hero-band">
            <nav class="site-nav" aria-label="Navigasi utama">
                <a class="brand-mark" href="{{ url('/') }}"><img class="mark" src="{{ asset('assets/img/logo.webp') }}" alt=""><span><strong>NesiaStore</strong><small>Retail workspace</small></span></a>
                <div class="auth-links"><a class="auth-button" href="{{ route('login') }}">Masuk</a><a class="auth-button register-button" href="{{ route('register') }}">Buat akun</a></div>
            </nav>
            <div class="hero-grid">
                <div class="content">
                    <span class="eyebrow"><i class="bi bi-shop-window" aria-hidden="true"></i>Workspace untuk operasional toko</span>
                    <h1>Urusan toko jadi <span>lebih praktis.</span></h1>
                    <p>Kelola produk, pantau stok, dan layani transaksi dari satu workspace yang dirancang untuk kerja harian.</p>
                    <div class="welcome-actions"><a class="welcome-action welcome-primary" href="{{ route('login') }}">Masuk ke aplikasi <i class="bi bi-arrow-right" aria-hidden="true"></i></a><a class="welcome-action welcome-secondary" href="{{ route('register') }}">Buat akun</a></div>
                    <div class="hero-proof"><span><i class="bi bi-check-circle-fill" aria-hidden="true"></i>Kasir terintegrasi</span><span><i class="bi bi-check-circle-fill" aria-hidden="true"></i>Katalog produk</span><span><i class="bi bi-check-circle-fill" aria-hidden="true"></i>Kontrol stok</span></div>
                </div>
                <div class="pos-scene" aria-label="Pratinjau workspace kasir">
                    <div class="pos-window">
                        <div class="pos-toolbar">
                            <div class="mini-brand"><span class="mini-mark"><i class="bi bi-shop" aria-hidden="true"></i></span><span>NesiaStore<small>POINT OF SALE</small></span></div>
                            <span class="pos-indicator"><i aria-hidden="true"></i>SIAP</span>
                        </div>
                        <div class="pos-content">
                            <div class="terminal-panel"><span class="terminal-label">TERMINAL KASIR</span><span class="terminal-icon"><i class="bi bi-pc-display-horizontal" aria-hidden="true"></i></span><strong>Kasir siap</strong><small>Mulai transaksi dengan cepat</small></div>
                            <div class="receipt-panel">
                                <div class="receipt-heading">Workspace toko <i class="bi bi-three-dots" aria-hidden="true"></i></div>
                                <div class="receipt-row"><i class="bi bi-grid-3x3-gap-fill" aria-hidden="true"></i><div><strong>Produk</strong><small>Katalog toko</small></div><i class="bi bi-chevron-right" aria-hidden="true"></i></div>
                                <div class="receipt-row"><i class="bi bi-box-seam-fill" aria-hidden="true"></i><div><strong>Persediaan</strong><small>Pantau stok</small></div><i class="bi bi-chevron-right" aria-hidden="true"></i></div>
                                <div class="receipt-row"><i class="bi bi-receipt" aria-hidden="true"></i><div><strong>Transaksi</strong><small>Alur kasir</small></div><i class="bi bi-chevron-right" aria-hidden="true"></i></div>
                                <div class="receipt-ready"><i class="bi bi-check-circle-fill" aria-hidden="true"></i>Siap untuk operasional</div>
                            </div>
                        </div>
                    </div>
                    <div class="floating-receipt"><span class="receipt-icon"><i class="bi bi-receipt-cutoff" aria-hidden="true"></i></span><span><strong>Kasir terintegrasi</strong><small>Transaksi dalam satu alur</small></span><i class="bi bi-check-circle-fill" aria-hidden="true"></i></div>
                </div>
            </div>
        </section>
        <section class="feature-strip" aria-label="Fitur workspace">
            <div class="feature"><span class="feature-icon"><i class="bi bi-lightning-charge-fill" aria-hidden="true"></i></span><span><strong>Kasir cepat</strong><span>Alur transaksi lebih ringkas.</span></span></div>
            <div class="feature"><span class="feature-icon"><i class="bi bi-grid-3x3-gap-fill" aria-hidden="true"></i></span><span><strong>Katalog teratur</strong><span>Produk mudah ditemukan.</span></span></div>
            <div class="feature"><span class="feature-icon"><i class="bi bi-box-seam-fill" aria-hidden="true"></i></span><span><strong>Stok terpantau</strong><span>Persediaan ada dalam kendali.</span></span></div>
        </section>
    </main>
</body>
</html>
