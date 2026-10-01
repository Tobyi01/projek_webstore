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
        :root { --ink: #12233f; --muted: #71809a; --line: #e6ebf2; --blue: #2167e8; --navy: #101d35; }
        * { box-sizing: border-box; }
        body { background: #f4f7fb; color: var(--ink); font-family: "Segoe UI", sans-serif; }
        .admin-shell { min-height: 100vh; }
        .sidebar { background: linear-gradient(165deg, #101d35 0%, #172b4d 100%); min-height: 100vh; padding: 1.5rem 1rem !important; position: relative; overflow: hidden; }
        .sidebar::after { background: rgba(54, 128, 255, .13); border-radius: 50%; content: ""; height: 17rem; position: absolute; right: -9rem; top: 18%; width: 17rem; }
        .sidebar-brand { align-items: center; color: #fff; display: flex; font-size: 1.25rem; font-weight: 800; gap: .65rem; letter-spacing: -.04em; position: relative; z-index: 1; }
        .sidebar-brand-mark { align-items: center; background: #4d9bff; border-radius: .7rem; box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .2); display: flex; height: 2.35rem; justify-content: center; width: 2.35rem; }
        .sidebar-caption { color: #8fa4c5; font-size: .65rem; letter-spacing: .08em; margin: .35rem 0 1.75rem 3rem; text-transform: uppercase; }
        .sidebar .nav-link { align-items: center; border-radius: .65rem; color: #aebbd0; display: flex; font-size: .78rem; gap: .55rem; margin-bottom: .35rem; padding: .75rem .85rem; position: relative; transition: background .25s, color .25s, transform .25s; z-index: 1; }
        .sidebar .nav-link:hover { background: rgba(255, 255, 255, .08); color: #fff; transform: translateX(3px); }
        .sidebar .nav-link.active { background: linear-gradient(100deg, #367ff0, #2d62c7); box-shadow: 0 .55rem 1.1rem rgba(17, 55, 125, .28); color: #fff; }
        .sidebar .nav-link i { font-size: 1rem; width: 1.1rem; }
        .sidebar hr { border-color: rgba(255, 255, 255, .1); margin: 1.5rem 0; position: relative; z-index: 1; }
        .sidebar form { position: relative; z-index: 1; }
        .sidebar .btn-danger { background: rgba(242, 91, 91, .15); border: 1px solid rgba(255, 137, 137, .22); color: #ffc3c3; font-size: .75rem; padding: .65rem; }
        .admin-main { animation: page-in .55s ease both; }
        .admin-main > * { animation: rise-in .55s ease both; }
        .card, .alert { border-color: var(--line) !important; border-radius: .75rem !important; }
        .card { box-shadow: 0 .65rem 2rem rgba(29, 55, 94, .06) !important; }
        .btn { border-radius: .5rem; font-weight: 600; transition: transform .2s, box-shadow .2s; }
        .btn:hover { box-shadow: 0 .4rem 1rem rgba(29, 55, 94, .12); transform: translateY(-2px); }
        @keyframes page-in { from { opacity: 0; } to { opacity: 1; } }
        @keyframes rise-in { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
        @media (max-width: 767.98px) { .sidebar { min-height: auto; padding: 1rem !important; } .sidebar-caption { margin-bottom: 1rem; } .sidebar .nav { display: grid; grid-template-columns: repeat(4, 1fr); gap: .35rem; } .sidebar .nav-link { justify-content: center; margin: 0; padding: .65rem .3rem; text-align: center; } .sidebar .nav-link i { display: block; } .sidebar .nav-link span { display: none; } .sidebar hr { margin: 1rem 0; } }
    </style>
</head>
<body>
<div class="container-fluid admin-shell">
    <div class="row">
        <aside class="col-md-3 col-lg-2 sidebar p-3">
            <a href="{{ route('dashboard') }}" class="sidebar-brand text-decoration-none">
                <span class="sidebar-brand-mark"><i class="bi bi-grid-1x2-fill"></i></span>
                <span>NesiaStore</span>
            </a>
            <div class="sidebar-caption">Retail workspace</div>
            <hr class="text-white">
            <nav class="nav nav-pills flex-column">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i><span>Dashboard</span>
                </a>
                <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam"></i><span>Produk</span>
                </a>
                <a href="{{ route('transactions.index') }}" class="nav-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i><span>Riwayat</span>
                </a>
                <a href="{{ route('kasir.index') }}" class="nav-link {{ request()->routeIs('kasir.*') ? 'active' : '' }}">
                    <i class="bi bi-cart3"></i><span>Kasir</span>
                </a>
            </nav>
            <hr class="text-white">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger w-100">
                    <i class="bi bi-box-arrow-right me-1"></i>Keluar
                </button>
            </form>
        </aside>
        <main class="col-md-9 col-lg-10 px-md-4 py-4 admin-main">
            @yield('content')
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
