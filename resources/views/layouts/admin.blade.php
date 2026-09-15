<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Produk - NesiaStore')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background: #f8f9fa; }
        .sidebar { min-height: 100vh; background: #0d6efd; }
        .sidebar .nav-link { color: rgba(255, 255, 255, .8); border-radius: 6px; margin-bottom: 4px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: rgba(255, 255, 255, .2); }
        @media (max-width: 767.98px) { .sidebar { min-height: auto; } }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <aside class="col-md-3 col-lg-2 sidebar p-3">
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center mb-3 text-white text-decoration-none fs-4 fw-bold">
                NesiaStore
            </a>
            <hr class="text-white">
            <nav class="nav nav-pills flex-column">
                <a href="{{ route('dashboard') }}" class="nav-link">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                </a>
                <a href="{{ route('products.index') }}" class="nav-link active">
                    <i class="bi bi-box-seam me-2"></i>Produk
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
        <main class="col-md-9 col-lg-10 px-md-4 py-4">
            @yield('content')
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
