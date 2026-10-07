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
            --cashier-deep: #0d3b2e;
            --cashier-accent: #20b06b;
            --cashier-soft: #eafff5;
        }

        * { box-sizing: border-box; }
        body {
            background: #f4f7fb;
            color: var(--ink);
            font-family: "Segoe UI", sans-serif;
        }
        body.admin-body { background: linear-gradient(180deg, #f3f7ff 0%, #edf4ff 100%); }
        body.cashier-body { background: linear-gradient(180deg, #f4fff9 0%, #edfdf5 100%); }
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
            background: linear-gradient(165deg, var(--cashier-deep) 0%, #12523c 100%);
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
        body.cashier-body .sidebar::after { background: rgba(32, 176, 107, .25); }
        .sidebar-brand { align-items: center; color: #fff; display: flex; font-size: 1.25rem; font-weight: 800; gap: .65rem; letter-spacing: -.04em; position: relative; z-index: 1; }
        .sidebar-brand-mark {
            align-items: center;
            border-radius: .7rem;
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .2);
            display: flex;
            height: 2.35rem;
            justify-content: center;
            width: 2.35rem;
        }
        body.admin-body .sidebar-brand-mark { background: linear-gradient(135deg, #4d9bff, #2b5ed8); }
        body.cashier-body .sidebar-brand-mark { background: linear-gradient(135deg, #33d27c, #1fa866); }
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
        body.cashier-body .role-pill { background: rgba(99, 234, 158, .18); border: 1px solid rgba(152, 240, 195, .25); color: #eaffe9; }
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
        body.cashier-body .sidebar .nav-link.active { background: linear-gradient(100deg, #2fcb7d, #1ca864); box-shadow: 0 .55rem 1.1rem rgba(17, 118, 75, .25); color: #fff; }
        .sidebar .nav-link i { font-size: 1rem; width: 1.1rem; }
        .sidebar hr { border-color: rgba(255, 255, 255, .1); margin: 1.5rem 0; position: relative; z-index: 1; }
        .sidebar form { position: relative; z-index: 1; }
        body.admin-body .sidebar .btn-danger { background: rgba(242, 91, 91, .15); border: 1px solid rgba(255, 137, 137, .22); color: #ffc3c3; font-size: .75rem; padding: .65rem; }
        body.cashier-body .sidebar .btn-danger { background: rgba(255, 255, 255, .12); border: 1px solid rgba(255,255,255,.2); color: #ebfff3; font-size: .75rem; padding: .65rem; }
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
    </style>
</head>
<body class="{{ $isAdmin ? 'admin-body' : 'cashier-body' }}">
<div class="admin-frame">
        <aside class="admin-sidebar sidebar">
            <div class="sidebar-top">
                <a href="{{ route('dashboard') }}" class="sidebar-brand text-decoration-none">
                    <span class="sidebar-brand-mark"><i class="bi {{ $isAdmin ? 'bi-grid-1x2-fill' : 'bi-cash-stack' }}"></i></span>
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
                @else
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
