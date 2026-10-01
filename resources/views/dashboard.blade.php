<x-app-layout>
    <div class="dashboard-wrap">
        <section class="dashboard-hero">
            <div class="dashboard-hero-inner">
                <div class="dashboard-intro">
                    <div class="dashboard-meta"><span class="dashboard-live"><i></i> WORKSPACE AKTIF</span><span class="dashboard-date">{{ now()->format('d / m / Y') }}</span></div>
                    <h1>Selamat datang,<br><span>{{ Auth::user()->name }}</span></h1>
                    <p>Semua kebutuhan operasional toko dalam satu tempat. Mau mulai dari mana hari ini?</p>
                    <a href="{{ route('kasir.index') }}" class="dashboard-primary"><i class="bi bi-receipt-cutoff" aria-hidden="true"></i>Mulai transaksi<i class="bi bi-arrow-up-right" aria-hidden="true"></i></a>
                </div>
                <aside class="activity-board" aria-label="Status aktivitas workspace">
                    <div class="activity-top"><div class="activity-brand"><span><i class="bi bi-shop" aria-hidden="true"></i></span><div><strong>NesiaStore</strong><small>RINGKASAN WORKSPACE</small></div></div><span class="activity-status"><i></i>Online</span></div>
                    <div class="activity-heading"><div><span>Aktivitas workspace</span><strong>Operasional toko</strong></div><i class="bi bi-three-dots" aria-hidden="true"></i></div>
                    <div class="activity-chart" aria-hidden="true"><span style="--bar-height: 34%"></span><span style="--bar-height: 55%"></span><span style="--bar-height: 43%"></span><span style="--bar-height: 78%"></span><span style="--bar-height: 61%"></span><span style="--bar-height: 92%"></span><span style="--bar-height: 70%"></span><span style="--bar-height: 100%"></span><span style="--bar-height: 82%"></span><span style="--bar-height: 65%"></span><span style="--bar-height: 88%"></span><span style="--bar-height: 74%"></span></div>
                    <div class="activity-legend"><span><i></i>Kasir</span><span><i></i>Produk</span><span><i></i>Stok</span></div>
                    <div class="activity-footer"><span><i class="bi bi-check-circle-fill" aria-hidden="true"></i>Semua layanan siap</span><i class="bi bi-arrow-up-right" aria-hidden="true"></i></div>
                </aside>
            </div>
        </section>

        <div class="dashboard-content">
            <section class="workspace-section" aria-labelledby="workspace-title">
                <div class="section-heading"><div><span class="dashboard-eyebrow">PINTASAN KERJA</span><h2 id="workspace-title">Lanjutkan pekerjaan</h2></div><span class="section-note">Pilih menu untuk mulai</span></div>
                <div class="quick-grid">
                    <a href="{{ route('kasir.index') }}" class="quick-card quick-card-primary"><span class="quick-icon"><i class="bi bi-receipt-cutoff" aria-hidden="true"></i></span><span class="quick-card-copy"><strong>Transaksi baru</strong><small>Buka halaman kasir</small></span><i class="bi bi-arrow-up-right quick-arrow" aria-hidden="true"></i></a>
                    @if (Auth::user()->role === 'admin')
                        <a href="{{ route('products.create') }}" class="quick-card"><span class="quick-icon"><i class="bi bi-plus-square" aria-hidden="true"></i></span><span class="quick-card-copy"><strong>Tambah produk</strong><small>Masukkan ke katalog</small></span><i class="bi bi-arrow-up-right quick-arrow" aria-hidden="true"></i></a>
                        <a href="{{ route('products.index') }}" class="quick-card"><span class="quick-icon"><i class="bi bi-grid-3x3-gap" aria-hidden="true"></i></span><span class="quick-card-copy"><strong>Kelola produk</strong><small>Lihat katalog toko</small></span><i class="bi bi-arrow-up-right quick-arrow" aria-hidden="true"></i></a>
                    @endif
                </div>
            </section>

            <section class="account-strip" aria-label="Status akun">
                <div class="account-identity"><span class="account-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span><div><span class="dashboard-eyebrow">AKUN AKTIF</span><strong>{{ Auth::user()->name }}</strong><small>{{ Auth::user()->email }}</small></div></div>
                <div class="account-message"><span class="account-check"><i class="bi bi-shield-check" aria-hidden="true"></i></span><div><strong>Workspace siap digunakan</strong><small>@if (Auth::user()->role === 'admin') Produk, stok, dan kasir dapat diakses dari menu utama. @else Kasir dan kelola transaksi dapat diakses dari menu utama. @endif</small></div></div>
                <a href="{{ route('profile.edit') }}" class="account-link">Pengaturan akun <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
            </section>
        </div>
    </div>

    <style>
        .dashboard-wrap { background: #f2f6fc; min-height: calc(100vh - 4rem); }
        .dashboard-hero { background: radial-gradient(ellipse at 82% 22%, rgba(68,151,242,.22), transparent 29%), linear-gradient(112deg, #0b2355, #124b9a 68%, #1769bd); color: #fff; overflow: hidden; position: relative; }
        .dashboard-hero::before { background-image: linear-gradient(rgba(255,255,255,.055) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.055) 1px, transparent 1px); background-size: 34px 34px; content: ""; inset: 0; mask-image: linear-gradient(90deg, transparent 25%, #000); pointer-events: none; position: absolute; }
        .dashboard-hero-inner { align-items: center; animation: dashboard-enter .7s ease both; display: grid; gap: 3rem; grid-template-columns: minmax(0, 1fr) minmax(20rem, .9fr); margin: 0 auto; max-width: 78rem; min-height: 20rem; padding: 2.6rem 1.5rem; position: relative; z-index: 1; }
        .dashboard-intro { max-width: 37rem; }
        .dashboard-meta { align-items: center; display: flex; gap: 1rem; margin-bottom: 1.2rem; }
        .dashboard-live, .dashboard-date { color: #c4dcff; font-size: .62rem; font-weight: 800; }
        .dashboard-live { align-items: center; background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.15); border-radius: 2rem; display: inline-flex; gap: .45rem; padding: .42rem .65rem; }
        .dashboard-live i, .activity-status i { animation: live-pulse 1.8s ease-in-out infinite; background: #57e3aa; border-radius: 50%; height: .42rem; width: .42rem; }
        .dashboard-date { color: #a9c9f5; font-weight: 600; }
        .dashboard-intro h1 { font-size: 2.85rem; font-weight: 800; line-height: 1.08; margin: 0 0 .8rem; }
        .dashboard-intro h1 span { color: #a9dbff; }
        .dashboard-intro p { color: #c8dafa; font-size: .88rem; line-height: 1.7; margin: 0; max-width: 29rem; }
        .dashboard-primary { align-items: center; background: #e2f4ff; border-radius: .5rem; box-shadow: 0 .5rem 1.3rem rgba(4,20,59,.2); color: #0f4387; display: inline-flex; font-size: .74rem; font-weight: 800; gap: .65rem; margin-top: 1.25rem; padding: .8rem .95rem; text-decoration: none; transition: background .2s, box-shadow .2s, transform .2s; }
        .dashboard-primary:hover { background: #fff; box-shadow: 0 .8rem 1.5rem rgba(4,20,59,.25); color: #0f4387; transform: translateY(-2px); }
        .dashboard-primary i:last-child { margin-left: .25rem; }
        .activity-board { animation: dashboard-enter .7s .13s ease both; background: rgba(8,32,78,.38); border: 1px solid rgba(207,229,255,.2); border-radius: .8rem; box-shadow: 0 1rem 2rem rgba(0,17,50,.12); padding: 1rem 1.1rem .8rem; }
        .activity-top, .activity-brand, .activity-heading, .activity-legend, .activity-footer { align-items: center; display: flex; }
        .activity-top, .activity-heading, .activity-footer { justify-content: space-between; }
        .activity-brand { gap: .55rem; }.activity-brand > span { align-items: center; background: #438ce8; border-radius: .4rem; color: #fff; display: flex; height: 2rem; justify-content: center; width: 2rem; }.activity-brand strong, .activity-brand small { display: block; }.activity-brand strong { font-size: .68rem; }.activity-brand small { color: #abc9f1; font-size: .48rem; letter-spacing: .08em; margin-top: .12rem; }
        .activity-status { align-items: center; color: #d2e8ff; display: flex; font-size: .56rem; gap: .35rem; }
        .activity-heading { margin-top: 1.35rem; }.activity-heading span, .activity-heading strong { display: block; }.activity-heading span { color: #afc9eb; font-size: .57rem; }.activity-heading strong { font-size: .82rem; margin-top: .2rem; }.activity-heading > i { color: #9dbce6; }
        .activity-chart { align-items: end; border-bottom: 1px solid rgba(213,232,255,.18); display: flex; gap: .55rem; height: 6.2rem; margin-top: .8rem; padding: 0 .2rem; }
        .activity-chart span { animation: chart-rise .75s cubic-bezier(.2,.7,.2,1) both; background: linear-gradient(180deg, #8ddbf6, #4a92e8); border-radius: .25rem .25rem 0 0; flex: 1; height: var(--bar-height); min-width: .35rem; transform-origin: bottom; }
        .activity-chart span:nth-child(2n) { animation-delay: .06s; opacity: .72; }.activity-chart span:nth-child(3n) { animation-delay: .12s; opacity: .86; }
        .activity-legend { gap: .95rem; margin-top: .65rem; }.activity-legend span { align-items: center; color: #b5cceb; display: flex; font-size: .52rem; gap: .35rem; }.activity-legend i { background: #8ddbf6; border-radius: 50%; height: .35rem; width: .35rem; }.activity-legend span:nth-child(2) i { background: #579be8; }.activity-legend span:nth-child(3) i { background: #bbdcff; }
        .activity-footer { border-top: 1px solid rgba(213,232,255,.16); color: #d6e8ff; font-size: .56rem; margin-top: .8rem; padding-top: .65rem; }.activity-footer span { align-items: center; display: flex; gap: .4rem; }.activity-footer span i { color: #70e0b0; }
        .dashboard-content { margin: 0 auto; max-width: 78rem; padding: 2rem 1.5rem 2.8rem; }
        .section-heading { align-items: end; display: flex; justify-content: space-between; margin-bottom: 1rem; }.dashboard-eyebrow { color: #3978bf; font-size: .59rem; font-weight: 800; letter-spacing: .12em; }.section-heading h2 { color: #18375e; font-size: 1.2rem; font-weight: 800; margin: .3rem 0 0; }.section-note { color: #8191a8; font-size: .62rem; }
        .quick-grid { display: grid; gap: .8rem; grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .quick-card { align-items: center; animation: dashboard-enter .6s ease both; background: #fff; border: 1px solid #dfe8f3; border-radius: .65rem; color: #1c3a61; display: flex; gap: .8rem; min-height: 5.2rem; padding: .85rem; text-decoration: none; transition: border-color .2s, box-shadow .2s, transform .2s; }
        .quick-card:nth-child(2) { animation-delay: .08s; }.quick-card:nth-child(3) { animation-delay: .16s; }
        .quick-card:hover { border-color: #8db8ed; box-shadow: 0 .7rem 1.5rem rgba(32,85,150,.1); color: #123e79; transform: translateY(-3px); }
        .quick-icon { align-items: center; background: #eaf3ff; border-radius: .5rem; color: #2671ce; display: flex; flex: 0 0 2.45rem; font-size: 1rem; height: 2.45rem; justify-content: center; transition: background .2s, color .2s, transform .2s; }
        .quick-card:hover .quick-icon { background: #dcecff; color: #1456a5; transform: scale(1.05); }
        .quick-card-primary .quick-icon { background: #1769dc; color: #fff; }.quick-card-primary:hover .quick-icon { background: #0e4fae; color: #fff; }
        .quick-card-copy { min-width: 0; }.quick-card-copy strong, .quick-card-copy small { display: block; }.quick-card-copy strong { font-size: .71rem; }.quick-card-copy small { color: #7b8da6; font-size: .59rem; margin-top: .2rem; }
        .quick-arrow { color: #91a6c1; font-size: .78rem; margin-left: auto; transition: color .2s, transform .2s; }.quick-card:hover .quick-arrow { color: #1769dc; transform: translate(2px, -2px); }
        .account-strip { align-items: center; animation: dashboard-enter .65s .2s ease both; background: #e8f1fc; border: 1px solid #dbe7f6; border-radius: .65rem; display: flex; gap: 1.5rem; justify-content: space-between; margin-top: 1.15rem; padding: .9rem 1rem; }
        .account-identity { align-items: center; display: flex; gap: .65rem; min-width: 12rem; }.account-avatar { align-items: center; background: #1f69c8; border-radius: .55rem; color: #fff; display: flex; flex: 0 0 2.4rem; font-size: .85rem; font-weight: 800; height: 2.4rem; justify-content: center; }.account-identity div > * { display: block; }.account-identity strong { color: #244468; font-size: .68rem; margin-top: .15rem; }.account-identity small { color: #7186a2; font-size: .58rem; margin-top: .1rem; }
        .account-message { align-items: center; display: flex; gap: .55rem; }.account-check { align-items: center; background: #d9edff; border-radius: 50%; color: #2674c4; display: flex; flex: 0 0 1.9rem; height: 1.9rem; justify-content: center; }.account-message strong, .account-message small { display: block; }.account-message strong { color: #31557b; font-size: .63rem; }.account-message small { color: #7589a2; font-size: .56rem; margin-top: .18rem; }
        .account-link { align-items: center; color: #2463a9; display: flex; font-size: .62rem; font-weight: 800; gap: .4rem; text-decoration: none; white-space: nowrap; }.account-link:hover { color: #0d4383; }.account-link i { transition: transform .2s; }.account-link:hover i { transform: translateX(3px); }
        @keyframes dashboard-enter { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes chart-rise { from { transform: scaleY(.15); } to { transform: scaleY(1); } }
        @keyframes live-pulse { 0%, 100% { box-shadow: 0 0 0 0 rgba(87,227,170,.35); } 50% { box-shadow: 0 0 0 .3rem rgba(87,227,170,0); } }
        @media (max-width: 900px) { .dashboard-hero-inner { gap: 2rem; grid-template-columns: minmax(0, 1fr) minmax(17rem, .9fr); padding-bottom: 2.3rem; padding-top: 2.3rem; }.dashboard-intro h1 { font-size: 2.45rem; }.dashboard-content { padding-left: 1.25rem; padding-right: 1.25rem; }.account-strip { align-items: start; flex-wrap: wrap; }.account-message { margin-left: auto; } }
        @media (max-width: 680px) { .dashboard-hero-inner { gap: 1.8rem; grid-template-columns: 1fr; padding: 2.1rem 1rem 2.5rem; }.dashboard-intro h1 { font-size: 2.3rem; }.activity-board { max-width: none; }.dashboard-content { padding: 1.5rem 1rem 2rem; }.quick-grid { grid-template-columns: 1fr; }.quick-card { min-height: 4.6rem; }.account-strip { align-items: start; flex-direction: column; gap: .9rem; }.account-message { margin-left: 0; }.account-link { border-top: 1px solid #d3e0f0; justify-content: space-between; padding-top: .75rem; width: 100%; } }
        @media (max-width: 380px) { .dashboard-meta { align-items: start; flex-direction: column; gap: .45rem; }.dashboard-intro h1 { font-size: 2rem; }.activity-board { padding: .8rem; }.activity-chart { gap: .35rem; }.section-heading { align-items: start; flex-direction: column; gap: .45rem; } }
        @media (prefers-reduced-motion: reduce) { *, *::before, *::after { animation-duration: .01ms !important; animation-iteration-count: 1 !important; scroll-behavior: auto !important; transition-duration: .01ms !important; } }
    </style>
</x-app-layout>
