@php
    $isAdmin = Auth::check() && Auth::user()->role === 'admin';
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'NesiaStore')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=20261021-2">
    <link rel="icon" type="image/png" sizes="128x128" href="{{ asset('assets/img/favicon.png') }}?v=20261021-2">
    <link rel="apple-touch-icon" href="{{ asset('assets/img/apple-touch-icon.png') }}?v=20261021-2">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --ink: #12233f;
            --muted: #71809a;
            --line: #e6ebf2;
            --blue: #2167e8;
            --navy: #101d35;
            --admin-deep: #0c1f39;
            --admin-accent: #3e8cff;
            --admin-soft: #edf4ff;
            --cashier-deep: #102855;
            --cashier-accent: #3478e5;
            --cashier-soft: #edf4ff;
        }

        * { box-sizing: border-box; }
        body {
            background: #f4f7fb;
            color: var(--ink);
            font-family: "Segoe UI", sans-serif;
        }
        body.admin-body { background: linear-gradient(180deg, #f3f7ff 0%, #edf4ff 100%); }
        body.cashier-body { background: linear-gradient(180deg, #f5f8ff 0%, #edf3ff 100%); }
        .admin-shell { min-height: 100vh; }
        .sidebar {
            min-height: 100vh;
            padding: 1.5rem 1rem !important;
            position: relative;
            overflow: hidden;
        }
        body.admin-body .sidebar {
            background: linear-gradient(165deg, var(--admin-deep) 0%, #1a2d4d 100%);
        }
        body.cashier-body .sidebar {
            background: linear-gradient(165deg, var(--cashier-deep) 0%, #1b4688 100%);
        }
        .sidebar::after {
            border-radius: 50%;
            content: "";
            height: 17rem;
            position: absolute;
            right: -9rem;
            top: 18%;
            width: 17rem;
            opacity: .18;
        }
        body.admin-body .sidebar::after { background: rgba(62, 140, 255, .25); }
        body.cashier-body .sidebar::after { background: rgba(75, 139, 245, .25); }
        .sidebar-brand { align-items: center; color: #fff; display: flex; font-size: 1.25rem; font-weight: 800; gap: .65rem; letter-spacing: -.04em; position: relative; z-index: 1; }
        .sidebar-brand-mark {
            align-items: center;
            border-radius: .7rem;
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .2);
            display: flex;
            height: 2.35rem;
            justify-content: center;
            object-fit: cover;
            width: 2.35rem;
        }
        body.admin-body .sidebar-brand-mark { background: linear-gradient(135deg, #4d9bff, #2b5ed8); }
        body.cashier-body .sidebar-brand-mark { background: linear-gradient(135deg, #63a0ff, #3478e5); }
        .role-pill {
            border-radius: 999px;
            display: inline-flex;
            font-size: .6rem;
            font-weight: 700;
            letter-spacing: .08em;
            margin-left: auto;
            padding: .35rem .65rem;
            position: relative;
            z-index: 1;
        }
        body.admin-body .role-pill { background: rgba(98, 163, 255, .18); border: 1px solid rgba(152, 199, 255, .25); color: #dfeeff; }
        body.cashier-body .role-pill { background: rgba(99, 160, 255, .18); border: 1px solid rgba(152, 190, 255, .25); color: #edf4ff; }
        .sidebar-top { align-items: center; display: flex; gap: .75rem; position: relative; z-index: 1; }
        .sidebar-caption {
            color: rgba(255, 255, 255, .72);
            font-size: .65rem;
            letter-spacing: .08em;
            margin: .7rem 0 1.5rem 0;
            text-transform: uppercase;
        }
        .sidebar .nav-link {
            align-items: center;
            border-radius: .65rem;
            color: rgba(255,255,255,.7);
            display: flex;
            font-size: .78rem;
            gap: .55rem;
            margin-bottom: .35rem;
            padding: .75rem .85rem;
            position: relative;
            transition: background .25s, color .25s, transform .25s;
            z-index: 1;
        }
        .sidebar .nav-link:hover { background: rgba(255, 255, 255, .08); color: #fff; transform: translateX(3px); }
        body.admin-body .sidebar .nav-link.active { background: linear-gradient(100deg, #367ff0, #2d62c7); box-shadow: 0 .55rem 1.1rem rgba(17, 55, 125, .28); color: #fff; }
        body.cashier-body .sidebar .nav-link.active { background: linear-gradient(100deg, #4389f5, #2864cc); box-shadow: 0 .55rem 1.1rem rgba(24, 73, 153, .25); color: #fff; }
        .sidebar .nav-link i { font-size: 1rem; width: 1.1rem; }
        .sidebar hr { border-color: rgba(255, 255, 255, .1); margin: 1.5rem 0; position: relative; z-index: 1; }
        .sidebar form { position: relative; z-index: 1; }
        body.admin-body .sidebar .btn-danger { background: rgba(242, 91, 91, .15); border: 1px solid rgba(255, 137, 137, .22); color: #ffc3c3; font-size: .75rem; padding: .65rem; }
        body.cashier-body .sidebar .btn-danger { background: rgba(255, 255, 255, .12); border: 1px solid rgba(255,255,255,.2); color: #edf4ff; font-size: .75rem; padding: .65rem; }
        .admin-main { animation: page-in .55s ease both; }
        .admin-main > * { animation: rise-in .55s ease both; }
        .card, .alert { border-color: var(--line) !important; border-radius: .75rem !important; }
        .card { box-shadow: 0 .65rem 2rem rgba(29, 55, 94, .06) !important; }
        .btn { border-radius: .5rem; font-weight: 600; transition: transform .2s, box-shadow .2s; }
        .btn:hover { box-shadow: 0 .4rem 1rem rgba(29, 55, 94, .12); transform: translateY(-2px); }
        @keyframes page-in { from { opacity: 0; } to { opacity: 1; } }
        @keyframes rise-in { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
        @media (max-width: 767.98px) { .sidebar { min-height: auto; padding: 1rem !important; } .sidebar-caption { margin-bottom: 1rem; } .sidebar .nav { display: grid; grid-template-columns: repeat(4, 1fr); gap: .35rem; } .sidebar .nav-link { justify-content: center; margin: 0; padding: .65rem .3rem; text-align: center; } .sidebar .nav-link i { display: block; } .sidebar .nav-link span { display: none; } .sidebar hr { margin: 1rem 0; } }
        .admin-frame { display: grid; grid-template-columns: 14.5rem minmax(0, 1fr); min-height: 100vh; }
        .admin-sidebar { display: flex; flex-direction: column; height: 100vh; overflow-y: auto; padding: 1.2rem .85rem !important; position: sticky; top: 0; }
        body.admin-body .admin-sidebar { background: #172b4d; }
        body.cashier-body .admin-sidebar { background: #4168d7; }
        .admin-sidebar::after { display: none; }
        .admin-sidebar .sidebar-brand { font-size: .95rem; letter-spacing: 0; }
        .admin-sidebar .sidebar-brand-mark { background: rgba(255,255,255,.16) !important; border-radius: 50%; box-shadow: none; height: 2rem; width: 2rem; }
        .admin-sidebar .role-pill { background: rgba(255,255,255,.13) !important; border: 1px solid rgba(255,255,255,.2) !important; color: #fff !important; font-size: .52rem; padding: .25rem .4rem; }
        .admin-sidebar .sidebar-caption { font-size: .58rem; margin: 1.35rem .6rem .55rem; }
        .admin-sidebar .nav { gap: .2rem; }
        .admin-sidebar .nav-link { border-radius: .3rem; font-size: .72rem; margin: 0; padding: .65rem .7rem; }
        body.admin-body .admin-sidebar .nav-link.active, body.cashier-body .admin-sidebar .nav-link.active { background: rgba(255,255,255,.18); box-shadow: inset 3px 0 #fff; }
        .admin-sidebar form { margin-top: auto; padding-top: 1rem; }
        .admin-sidebar .btn-danger { background: rgba(255,255,255,.12) !important; border-color: rgba(255,255,255,.22) !important; color: #fff !important; }
        .admin-workspace { min-width: 0; }
        .admin-topbar { align-items: center; background: #fff; border-bottom: 1px solid #e8edf4; display: flex; justify-content: space-between; min-height: 4rem; padding: .6rem 1.5rem; }
        .topbar-heading { color: #59657a; font-size: .82rem; font-weight: 600; }
        .topbar-account { align-items: center; color: #657187; display: flex; font-size: .68rem; gap: .85rem; }
        .topbar-account a { align-items: center; color: #4b5f87; display: inline-flex; gap: .45rem; text-decoration: none; }
        .topbar-avatar { align-items: center; background: #e8efff; border-radius: 50%; color: #4168d7; display: inline-flex; font-size: .65rem; font-weight: 800; height: 2rem; justify-content: center; width: 2rem; }
        .admin-main { padding: 1.4rem 1.5rem 2rem !important; }
        @media (max-width: 767.98px) {
            .admin-frame { grid-template-columns: 1fr; }
            .admin-sidebar { height: auto; min-height: 0; overflow: visible; padding: .8rem !important; position: relative; }
            .admin-sidebar .sidebar-top { gap: .5rem; }
            .admin-sidebar .sidebar-caption, .admin-sidebar hr { display: none; }
            .admin-sidebar .nav { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); margin-top: .8rem; }
            .admin-sidebar .nav-link { flex-direction: column; gap: .2rem; padding: .55rem .25rem; }
            .admin-sidebar .nav-link span { display: inline; font-size: .58rem; }
            .admin-sidebar form { margin-top: .5rem; padding: 0; }
            .admin-sidebar form button { padding: .4rem !important; }
            .admin-topbar { min-height: 3.5rem; padding: .55rem .85rem; }
            .admin-main { padding: 1rem .8rem 1.5rem !important; }
        }
        body.admin-body, body.cashier-body { background: #f5f7fb; color: #17243a; }
        .admin-frame { grid-template-columns: 15.5rem minmax(0, 1fr); }
        .admin-sidebar { background: #111c32 !important; border-right: 1px solid rgba(255,255,255,.06); padding: 1.35rem 1rem !important; }
        body.cashier-body .admin-sidebar { background: linear-gradient(165deg, #102855 0%, #1b4688 100%) !important; }
        .admin-sidebar .sidebar-top { gap: .65rem; }
        .admin-sidebar .sidebar-brand { font-size: 1.03rem; letter-spacing: -.025em; }
        .admin-sidebar .sidebar-brand-mark { background: #fff !important; border: 1px solid rgba(255,255,255,.3); border-radius: .7rem; height: 2.35rem; object-fit: contain; padding: .12rem; width: 2.35rem; }
        .admin-sidebar .role-pill { font-size: .58rem; letter-spacing: .07em; padding: .3rem .48rem; }
        .admin-sidebar .sidebar-caption { color: rgba(231,239,255,.54); font-size: .62rem; letter-spacing: .11em; margin: 1.55rem .65rem .65rem; }
        .admin-sidebar .nav { gap: .3rem; }
        .admin-sidebar .nav-link { border: 1px solid transparent; border-radius: .7rem; color: rgba(239,244,255,.68); font-size: .81rem; gap: .75rem; min-height: 2.8rem; padding: .7rem .8rem; }
        .admin-sidebar .nav-link i { color: rgba(219,231,255,.72); font-size: 1.05rem; }
        .admin-sidebar .nav-link:hover { background: rgba(255,255,255,.07); color: #fff; transform: translateX(2px); }
        body.admin-body .admin-sidebar .nav-link.active { background: rgba(78,139,255,.17); border-color: rgba(111,162,255,.2); box-shadow: none; color: #fff; }
        body.admin-body .admin-sidebar .nav-link.active i { color: #85b3ff; }
        body.cashier-body .admin-sidebar .nav-link.active { background: rgba(78,139,255,.17); border-color: rgba(111,162,255,.2); box-shadow: none; color: #fff; }
        body.cashier-body .admin-sidebar .nav-link.active i { color: #85b3ff; }
        .admin-sidebar hr { border-color: rgba(255,255,255,.1); margin: 1.25rem 0; }
        .admin-sidebar .btn-danger { border-radius: .65rem; font-size: .78rem; padding: .7rem !important; }
        .admin-workspace { min-height: 100vh; }
        .admin-topbar { background: rgba(255,255,255,.94); border-bottom-color: #e9edf4; min-height: 4.35rem; padding: .65rem 2rem; position: sticky; top: 0; z-index: 20; }
        .topbar-heading { color: #293851; font-size: .9rem; font-weight: 700; }
        .topbar-account { color: #8290a5; font-size: .75rem; gap: 1rem; }
        .topbar-account > span:first-child { background: #f3f6fa; border: 1px solid #e9edf4; border-radius: 2rem; color: #66758c; padding: .45rem .7rem; }
        .topbar-account a { color: #33445e; font-weight: 600; gap: .55rem; }
        .topbar-avatar { background: #e9f0ff; color: #356dd4; font-size: .72rem; height: 2.2rem; width: 2.2rem; }
        body.cashier-body .topbar-avatar { background: #e9f0ff; color: #356dd4; }
        .admin-main { margin: 0 auto; max-width: 1800px; padding: 1.75rem 2rem 2.5rem !important; width: 100%; }
        .card, .alert { border-radius: .9rem !important; }
        .card { box-shadow: 0 .5rem 1.7rem rgba(29,55,94,.055) !important; }
        .btn { border-radius: .6rem; }
        .table { --bs-table-bg: transparent; }
        .table thead th { background: #f7f9fc; border-bottom-color: #e8edf4; color: #68778d; font-size: .72rem; font-weight: 700; letter-spacing: .025em; padding: .85rem .8rem; white-space: nowrap; }
        .table tbody td { border-bottom-color: #edf0f5; color: #46556c; padding: .85rem .8rem; }
        .table tbody tr:last-child td { border-bottom: 0; }
        @media (max-width: 767.98px) {
            .admin-frame { grid-template-columns: 1fr; }
            .admin-sidebar { padding: .8rem !important; }
            .admin-sidebar .nav { grid-template-columns: repeat(3, minmax(0, 1fr)); }
            .admin-sidebar .nav-link { border-radius: .6rem; }
            .admin-sidebar .nav-link.active { box-shadow: none !important; }
            .admin-main { padding: 1.1rem .85rem 1.5rem !important; }
            .admin-topbar { padding: .55rem .9rem; }
            .topbar-account { gap: .45rem; }
            .topbar-account > span:first-child { display: none; }
        }
    </style>
</head>
<body class="{{ $isAdmin ? 'admin-body' : 'cashier-body' }}">
<div class="admin-frame">
        <aside class="admin-sidebar sidebar">
            <div class="sidebar-top">
                <a href="{{ route('dashboard') }}" class="sidebar-brand text-decoration-none">
                    <img src="{{ asset('assets/img/nesia-logo.png') }}" alt="" class="sidebar-brand-mark">
                    <span>NesiaStore</span>
                </a>
                <span class="role-pill">{{ $isAdmin ? 'ADMIN' : 'KASIR' }}</span>
            </div>
            <div class="sidebar-caption">{{ $isAdmin ? 'Retail workspace' : 'Point of sale' }}</div>
            <hr class="text-white">
            <nav class="nav nav-pills flex-column">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard', 'admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i><span>Dashboard</span>
                </a>
                @if ($isAdmin)
                    <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam"></i><span>Produk</span>
                    </a>
                    <a href="{{ route('transactions.index') }}" class="nav-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
                        <i class="bi bi-clock-history"></i><span>Riwayat</span>
                    </a>
                    <a href="{{ route('admin.cashiers.index') }}" class="nav-link {{ request()->routeIs('admin.cashiers.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i><span>Kasir</span>
                    </a>
                @else
                    <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam"></i><span>Produk</span>
                    </a>
                    <a href="{{ route('kasir.index') }}" class="nav-link {{ request()->routeIs('kasir.*') ? 'active' : '' }}">
                        <i class="bi bi-cart3"></i><span>Kasir</span>
                    </a>
                @endif
            </nav>
            <hr class="text-white">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger w-100">
                    <i class="bi bi-box-arrow-right me-1"></i>Keluar
                </button>
            </form>
        </aside>
        <div class="admin-workspace">
            <header class="admin-topbar">
                <span class="topbar-heading">@yield('page-heading', 'NesiaStore')</span>
                <div class="topbar-account">
                    <span>{{ now()->translatedFormat('d M Y') }}</span>
                    <a href="{{ route('profile.edit') }}" aria-label="Pengaturan akun {{ Auth::user()->name }}">
                        <span class="topbar-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                        <span>{{ Auth::user()->name }}</span>
                    </a>
                </div>
            </header>
            <main class="admin-main">
                @yield('content')
            </main>
        </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
