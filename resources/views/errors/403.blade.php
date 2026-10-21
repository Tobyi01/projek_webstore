<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#090d1d">
    <title>403 - Akses ditolak | NesiaStore</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=20261021-2">
    <link rel="icon" type="image/png" sizes="128x128" href="{{ asset('assets/img/favicon.png') }}?v=20261021-2">
    <link rel="apple-touch-icon" href="{{ asset('assets/img/apple-touch-icon.png') }}?v=20261021-2">
    <style>
        :root {
            color-scheme: dark;
            font-family: "Segoe UI", Arial, sans-serif;
            background: #090d1d;
            color: #f7f8ff;
        }

        * { box-sizing: border-box; }
        body {
            align-items: center;
            background:
                radial-gradient(ellipse at 50% 48%, rgba(68, 50, 153, .35), transparent 42%),
                radial-gradient(ellipse at 15% 15%, rgba(0, 191, 255, .14), transparent 35%),
                radial-gradient(ellipse at 85% 85%, rgba(255, 44, 119, .14), transparent 35%),
                #090d1d;
            display: flex;
            justify-content: center;
            margin: 0;
            min-height: 100vh;
            overflow: hidden;
            padding: 1.5rem;
        }

        body::before, body::after {
            content: "";
            inset: -50%;
            pointer-events: none;
            position: fixed;
        }

        body::before {
            animation: aurora 14s ease-in-out infinite alternate;
            background: conic-gradient(from 0deg at 50% 50%, transparent 0 18%, rgba(41, 202, 255, .08) 23%, transparent 30% 48%, rgba(255, 65, 160, .08) 54%, transparent 62% 78%, rgba(142, 91, 255, .09) 84%, transparent 92%);
            filter: blur(50px);
        }

        body::after {
            background-image: radial-gradient(rgba(255, 255, 255, .38) .8px, transparent .8px);
            background-size: 39px 39px;
            mask-image: linear-gradient(transparent, #000 20%, #000 80%, transparent);
            opacity: .2;
        }

        .scene {
            isolation: isolate;
            max-width: 46rem;
            position: relative;
            text-align: center;
            width: 100%;
        }

        .orbit {
            animation: orbit-spin 19s linear infinite;
            border: 1px solid rgba(167, 157, 255, .25);
            border-radius: 50%;
            height: min(76vw, 29rem);
            left: 50%;
            pointer-events: none;
            position: absolute;
            top: 45%;
            transform: translate(-50%, -50%) rotateX(67deg);
            width: min(76vw, 29rem);
        }

        .orbit::before, .orbit::after {
            border-radius: 50%;
            content: "";
            height: .8rem;
            position: absolute;
            width: .8rem;
        }

        .orbit::before { background: #41e6ff; box-shadow: 0 0 12px #41e6ff, 0 0 32px #41e6ff; left: 17%; top: 21%; }
        .orbit::after { animation: pulse 1.7s ease-in-out infinite; background: #ff48a8; box-shadow: 0 0 14px #ff48a8, 0 0 36px #ff48a8; bottom: 16%; right: 18%; }
        .orbit-two { animation: orbit-spin 25s linear infinite reverse; border-color: rgba(69, 226, 255, .12); height: min(62vw, 23rem); width: min(62vw, 23rem); }
        .orbit-two::before { background: #a982ff; box-shadow: 0 0 14px #a982ff, 0 0 35px #a982ff; left: auto; right: 9%; top: 28%; }
        .orbit-two::after { background: #ffc857; box-shadow: 0 0 14px #ffc857, 0 0 35px #ffc857; bottom: 20%; right: auto; left: 14%; }

        .spark {
            animation: sparkle 3.2s ease-in-out infinite;
            background: #fff;
            border-radius: 50%;
            box-shadow: 0 0 9px 2px currentColor, 0 0 24px currentColor;
            color: #62eaff;
            height: .32rem;
            left: 12%;
            position: absolute;
            top: 16%;
            width: .32rem;
        }

        .spark:nth-of-type(2) { animation-delay: -1s; color: #ff55bd; left: 84%; top: 20%; }
        .spark:nth-of-type(3) { animation-delay: -2s; color: #b79aff; left: 8%; top: 73%; }
        .spark:nth-of-type(4) { animation-delay: -.5s; color: #ffc857; left: 91%; top: 68%; }
        .spark:nth-of-type(5) { animation-delay: -1.7s; color: #62eaff; left: 28%; top: 89%; }
        .spark:nth-of-type(6) { animation-delay: -2.5s; color: #ff55bd; left: 72%; top: 91%; }

        .content { animation: content-in .9s cubic-bezier(.2,.8,.2,1) both; position: relative; z-index: 1; }
        .badge {
            align-items: center;
            animation: badge-glow 2s ease-in-out infinite alternate;
            background: rgba(255, 77, 133, .1);
            border: 1px solid rgba(255, 108, 161, .38);
            border-radius: 999px;
            color: #ff9cbd;
            display: inline-flex;
            font-size: .7rem;
            font-weight: 800;
            gap: .55rem;
            letter-spacing: .18em;
            margin-bottom: 1rem;
            padding: .55rem .9rem;
            text-transform: uppercase;
        }

        .badge-dot { animation: pulse 1.1s ease-in-out infinite; background: #ff528c; border-radius: 50%; height: .48rem; width: .48rem; }
        h1 {
            animation: number-in 1.1s cubic-bezier(.18,.8,.24,1.2) both, text-glow 3s ease-in-out infinite alternate;
            background: linear-gradient(100deg, #fff 8%, #9cecff 37%, #c9a6ff 62%, #ff8bc2 92%);
            background-clip: text;
            color: transparent;
            filter: drop-shadow(0 0 25px rgba(129, 105, 255, .4));
            font-size: clamp(7rem, 24vw, 13rem);
            font-weight: 900;
            letter-spacing: -.095em;
            line-height: .95;
            margin: 0 0 1.15rem -.1em;
            position: relative;
        }

        h1::after {
            animation: glitch 4s steps(1, end) infinite;
            color: rgba(255, 66, 163, .62);
            content: "403";
            inset: 0;
            pointer-events: none;
            position: absolute;
            text-shadow: 3px 0 #1bd9ff;
        }

        h2 { font-size: clamp(1.35rem, 4vw, 2rem); letter-spacing: -.035em; margin: 0; }
        .description { color: #a9b1ca; font-size: .98rem; line-height: 1.7; margin: .7rem auto 1.7rem; max-width: 27rem; }
        .actions { display: flex; flex-wrap: wrap; gap: .75rem; justify-content: center; }
        .button {
            align-items: center;
            border: 1px solid rgba(255,255,255,.16);
            border-radius: .8rem;
            color: white;
            display: inline-flex;
            font: inherit;
            font-size: .88rem;
            font-weight: 750;
            gap: .55rem;
            justify-content: center;
            min-height: 3rem;
            padding: .75rem 1.1rem;
            text-decoration: none;
            transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
        }

        .button:hover { border-color: rgba(255,255,255,.42); box-shadow: 0 9px 30px rgba(0,0,0,.22); color: white; transform: translateY(-3px); }
        .button-primary { background: linear-gradient(115deg, #674cff, #a342e8 55%, #ed4ca1); border: 0; box-shadow: 0 8px 26px rgba(137, 67, 238, .33); }
        .button-secondary { background: rgba(255,255,255,.055); }
        .button svg { height: 1.05rem; width: 1.05rem; }
        .footnote { color: #68718b; font-size: .72rem; letter-spacing: .04em; margin-top: 2.25rem; }

        @keyframes aurora { to { transform: rotate(22deg) scale(1.08); } }
        @keyframes orbit-spin { to { rotate: 1 1 0 360deg; } }
        @keyframes pulse { 50% { opacity: .45; scale: .68; } }
        @keyframes sparkle { 0%, 100% { opacity: .2; scale: .55; } 50% { opacity: 1; scale: 1.5; } }
        @keyframes content-in { from { opacity: 0; translate: 0 24px; } to { opacity: 1; translate: 0 0; } }
        @keyframes number-in { from { opacity: 0; scale: .72; rotate: -7deg; } to { opacity: 1; scale: 1; rotate: 0; } }
        @keyframes badge-glow { to { box-shadow: 0 0 30px rgba(255, 78, 143, .18); } }
        @keyframes text-glow { to { filter: drop-shadow(0 0 38px rgba(129, 105, 255, .72)); } }
        @keyframes glitch { 0%, 91%, 94%, 97%, 100% { clip-path: inset(0 0 100% 0); transform: translate(0); } 92% { clip-path: inset(15% 0 68% 0); transform: translate(-5px, 2px); } 95% { clip-path: inset(58% 0 20% 0); transform: translate(5px, -2px); } 98% { clip-path: inset(38% 0 45% 0); transform: translate(-3px, 0); } }

        @media (max-width: 480px) {
            body { padding: 1.2rem; }
            .description { font-size: .9rem; }
            .orbit { height: 78vw; width: 78vw; }
            .orbit-two { height: 62vw; width: 62vw; }
            .footnote { margin-top: 1.7rem; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: .01ms !important; animation-iteration-count: 1 !important; scroll-behavior: auto !important; }
        }
    </style>
</head>
<body>
    <main class="scene">
        <div class="orbit" aria-hidden="true"></div>
        <div class="orbit orbit-two" aria-hidden="true"></div>
        <span class="spark" aria-hidden="true"></span>
        <span class="spark" aria-hidden="true"></span>
        <span class="spark" aria-hidden="true"></span>
        <span class="spark" aria-hidden="true"></span>
        <span class="spark" aria-hidden="true"></span>
        <span class="spark" aria-hidden="true"></span>

        <section class="content" aria-labelledby="error-title">
            <div class="badge"><span class="badge-dot" aria-hidden="true"></span> Zona Terlarang</div>
            <h1 aria-label="Error 403">403</h1>
            <h2 id="error-title">Ups, aksesnya terkunci!</h2>
            <p class="description">
                Anda tidak memiliki izin untuk membuka halaman ini.
                Kembali ke halaman yang bisa Anda akses, lalu lanjutkan petualangan.
            </p>
            <div class="actions">
                <a class="button button-primary" href="{{ route('dashboard') }}">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1V10Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Kembali ke Dashboard
                </a>
                <a class="button button-secondary" href="{{ url()->previous() }}">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M19 12H5m0 0 7 7m-7-7 7-7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Halaman Sebelumnya
                </a>
            </div>
            <p class="footnote">NESIASTORE <span aria-hidden="true">·</span> AKSES TERBATAS</p>
        </section>
    </main>
</body>
</html>
