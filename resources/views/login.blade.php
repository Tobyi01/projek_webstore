<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - NesiaStore</title>
    <style>
        :root {
            --blue: #1769ed;
            --blue-dark: #0d3374;
            --blue-soft: #eaf3ff;
            --ink: #102858;
            --muted: #74819c;
            --line: #dce5f1;
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            color: var(--ink);
            background: #f4f8fe;
            font-family: "Trebuchet MS", "Segoe UI", sans-serif;
        }
        button, input { font: inherit; }
        .login-shell { display: grid; min-height: 100vh; grid-template-columns: minmax(0, 1.05fr) minmax(420px, .95fr); }
        .showcase {
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 100vh;
            padding: clamp(2rem, 5vw, 4.75rem) clamp(2rem, 7vw, 7rem);
            overflow: hidden;
            background: linear-gradient(145deg, #1e70e5 0%, #2364c7 58%, #0e3679 100%);
        }
        .showcase::before {
            position: absolute;
            top: -11rem;
            right: -9rem;
            width: 34rem;
            height: 34rem;
            border: 2rem solid rgba(255, 255, 255, .1);
            border-radius: 50%;
            content: "";
        }
        .showcase::after {
            position: absolute;
            right: -5rem;
            bottom: -11rem;
            width: 32rem;
            height: 22rem;
            border-radius: 50% 50% 0 0;
            background: rgba(7, 37, 91, .4);
            content: "";
        }
        .brand { position: relative; z-index: 1; display: flex; align-items: center; gap: .8rem; color: #fff; }
        .brand img { width: 3.35rem; height: 3.35rem; padding: .48rem; border-radius: .9rem; background: #fff; }
        .brand strong { display: block; font-size: 1.35rem; letter-spacing: -.04em; }
        .brand span { display: block; margin-top: .15rem; color: #d8e8ff; font-size: .68rem; }
        .intro { position: relative; z-index: 1; max-width: 34rem; margin: auto 0 2rem; }
        .intro h1 { max-width: 27rem; margin: 0 0 1rem; color: #fff; font-size: clamp(2.15rem, 4vw, 3.7rem); line-height: 1.04; letter-spacing: -.06em; }
        .intro h1 span { color: #8dc2ff; }
        .intro p { max-width: 29rem; margin: 0; color: #dceaff; font-size: 1rem; line-height: 1.65; }
        .features { display: flex; gap: .7rem; margin-top: 2rem; }
        .feature { width: 5.8rem; color: #fff; font-size: .68rem; line-height: 1.3; text-align: center; }
        .feature-icon { display: grid; width: 2.65rem; height: 2.65rem; margin: 0 auto .45rem; place-items: center; border-radius: .75rem; color: var(--blue); background: #dcecff; font-size: 1.2rem; }
        .pos-scene { position: relative; z-index: 1; align-self: center; width: min(100%, 34rem); height: 12rem; margin-top: 1rem; }
        .counter { position: absolute; right: 0; bottom: 0; left: 0; height: 4.7rem; border-radius: .7rem .7rem 0 0; background: linear-gradient(#d69d66, #a96b43); box-shadow: inset 0 1rem 1.5rem rgba(255, 255, 255, .16); }
        .terminal { position: absolute; bottom: 3.25rem; left: 16%; width: 13rem; height: 7rem; padding: .7rem; border-radius: .75rem; background: #172238; box-shadow: .8rem .8rem 0 rgba(9, 33, 80, .35); transform: perspective(15rem) rotateX(3deg) rotateY(9deg); }
        .terminal-screen { display: grid; grid-template-columns: repeat(3, 1fr); gap: .3rem; height: 5.4rem; padding: .45rem; border-radius: .25rem; background: #e9f3ff; }
        .terminal-screen span { display: block; border-radius: .15rem; background: #fff; box-shadow: 0 1px 3px rgba(24, 63, 124, .15); }
        .terminal-screen span:nth-child(2), .terminal-screen span:nth-child(5) { background: #8cc3ff; }
        .scanner { position: absolute; right: 14%; bottom: 3.35rem; width: 2.9rem; height: 6rem; border-radius: 1.2rem .7rem .5rem .5rem; background: #202c42; transform: rotate(8deg); }
        .scanner::before { position: absolute; top: .75rem; left: .55rem; width: 1.8rem; height: 2.5rem; border-radius: .35rem; background: #2d415d; content: ""; }
        .form-panel { display: grid; place-items: center; padding: 2rem; background: radial-gradient(circle at top right, #e6f1ff 0, transparent 28%), #f7fbff; }
        .form-card { width: min(100%, 31rem); padding: clamp(2rem, 4vw, 3rem); border: 1px solid #edf2f8; border-radius: .85rem; background: rgba(255, 255, 255, .95); box-shadow: 0 1.25rem 3rem rgba(27, 65, 123, .12); }
        .form-mark { display: grid; width: 3.9rem; height: 3.9rem; margin: 0 auto 1rem; place-items: center; border: 3px solid var(--blue); border-radius: .7rem; color: var(--blue); font-size: 2.1rem; font-weight: 700; }
        .form-card h2 { margin: 0; color: var(--ink); font-size: 1.85rem; letter-spacing: -.05em; text-align: center; }
        .form-card > p { margin: .5rem 0 1.9rem; color: var(--muted); font-size: .78rem; text-align: center; }
        .field { margin-bottom: 1.1rem; }
        .field label { display: block; margin-bottom: .45rem; color: #52617d; font-size: .72rem; font-weight: 700; }
        .input-wrap { position: relative; }
        .input-wrap span { position: absolute; top: 50%; left: .85rem; color: #536684; font-size: .9rem; transform: translateY(-50%); }
        .input-wrap input { width: 100%; height: 2.8rem; padding: 0 .9rem 0 2.45rem; border: 1px solid var(--line); border-radius: .45rem; outline: none; color: var(--ink); background: #fff; font-size: .75rem; transition: border-color .2s, box-shadow .2s; }
        .input-wrap input:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(23, 105, 237, .12); }
        .form-options { display: flex; align-items: center; justify-content: space-between; margin: -.25rem 0 1.25rem; color: #62718c; font-size: .7rem; }
        .remember { display: flex; align-items: center; gap: .4rem; }
        .remember input { accent-color: var(--blue); }
        a { color: var(--blue); font-weight: 700; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .submit { width: 100%; height: 2.85rem; border: 0; border-radius: .45rem; color: #fff; background: var(--blue); box-shadow: 0 .45rem 1rem rgba(23, 105, 237, .23); cursor: pointer; font-size: .78rem; font-weight: 700; transition: transform .2s, background .2s; }
        .submit:hover { background: #0d58d2; transform: translateY(-1px); }
        .divider { display: flex; align-items: center; gap: .75rem; margin: 1.2rem 0; color: #8a96aa; font-size: .68rem; }
        .divider::before, .divider::after { height: 1px; flex: 1; background: var(--line); content: ""; }
        .back-link { display: block; padding: .78rem; border: 1px solid var(--line); border-radius: .45rem; color: #596985; font-size: .7rem; text-align: center; }
        .back-link:hover { background: var(--blue-soft); text-decoration: none; }
        .alert { margin-bottom: 1rem; padding: .75rem; border-radius: .4rem; color: #9d2f2f; background: #fff0f0; font-size: .72rem; }
        @media (max-width: 850px) { .login-shell { grid-template-columns: 1fr; } .showcase { min-height: auto; padding: 2rem 1.5rem 0; } .intro { margin-top: 4rem; } .pos-scene { margin-top: 2rem; } .form-panel { padding: 2rem 1.25rem; } }
        @media (max-width: 480px) { .features { gap: .15rem; } .feature { flex: 1; } .form-card { padding: 1.5rem; } }
    </style>
</head>
<body>
    <main class="login-shell">
        <section class="showcase" aria-label="NesiaStore">
            <div class="brand">
                <img src="{{ asset('assets/img/logo.webp') }}" alt="NesiaStore">
                <div><strong>NesiaStore</strong><span>Sistem Kasir Modern & Praktis</span></div>
            </div>
            <div class="intro">
                <h1>Kelola Transaksi,<br><span>Lebih Mudah & Cepat</span></h1>
                <p>NesiaStore membantu Anda mengelola transaksi, stok barang, dan laporan penjualan dengan lebih efisien dalam satu sistem.</p>
                <div class="features">
                    <div class="feature"><div class="feature-icon">▣</div>Transaksi<br>Penjualan</div>
                    <div class="feature"><div class="feature-icon">◆</div>Manajemen<br>Stok</div>
                    <div class="feature"><div class="feature-icon">▤</div>Laporan<br>Penjualan</div>
                    <div class="feature"><div class="feature-icon">✓</div>Aman &<br>Terpercaya</div>
                </div>
            </div>
            <div class="pos-scene" aria-hidden="true">
                <div class="terminal"><div class="terminal-screen"><span></span><span></span><span></span><span></span><span></span><span></span></div></div>
                <div class="scanner"></div><div class="counter"></div>
            </div>
        </section>
        <section class="form-panel">
            <div class="form-card">
                <div class="form-mark">▣</div>
                <h2>Selamat Datang</h2>
                <p>Silakan login untuk melanjutkan ke sistem kasir</p>
                @if ($errors->any())
                    <div class="alert">{{ $errors->first() }}</div>
                @endif
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="field">
                        <label for="email">Email</label>
                        <div class="input-wrap"><span>✉</span><input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email Anda" required autofocus autocomplete="email"></div>
                    </div>
                    <div class="field">
                        <label for="password">Password</label>
                        <div class="input-wrap"><span>▣</span><input id="password" type="password" name="password" placeholder="Masukkan password Anda" required autocomplete="current-password"></div>
                    </div>
                    <div class="form-options">
                        <label class="remember"><input type="checkbox" name="remember"> Ingat saya</label>
                        <a href="{{ route('password.request') }}">Lupa password?</a>
                    </div>
                    <button class="submit" type="submit">Login <span aria-hidden="true">→</span></button>
                </form>
                <div class="divider">atau</div>
                <a class="back-link" href="{{ url('/') }}">← &nbsp; Kembali ke Beranda</a>
            </div>
        </section>
    </main>
</body>
</html>