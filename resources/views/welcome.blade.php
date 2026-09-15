<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <style>
        * { box-sizing: border-box; }
        html, body { margin: 0; min-height: 100%; }
        body { overflow: hidden; background: #ff2d20; font-family: Arial, Helvetica, sans-serif; }
        .splash { position: relative; display: grid; min-height: 100vh; place-items: center; overflow: hidden; }
        .auth-links { position: absolute; top: 1.25rem; right: 1.5rem; z-index: 1; display: flex; gap: .65rem; align-items: center; }
        .auth-button { padding: .65rem 1.2rem; border: 1px solid rgb(255 255 255 / .8); border-radius: .35rem; color: #fff; font-size: .9rem; font-weight: 700; text-decoration: none; transition: background-color .2s ease, color .2s ease; }
        .auth-button:hover { background: #fff; color: #ff2d20; }
        .register-button { background: #fff; color: #ff2d20; }
        .register-button:hover { background: transparent; color: #fff; }
        .pattern { position: absolute; inset: -20%; opacity: .14; transform: rotate(-8deg) scale(1.3); background-image: linear-gradient(30deg, #b91c1c 12%, transparent 12.5%, transparent 87%, #b91c1c 87.5%), linear-gradient(150deg, #b91c1c 12%, transparent 12.5%, transparent 87%, #b91c1c 87.5%), linear-gradient(30deg, #b91c1c 12%, transparent 12.5%, transparent 87%, #b91c1c 87.5%), linear-gradient(150deg, #b91c1c 12%, transparent 12.5%, transparent 87%, #b91c1c 87.5%), linear-gradient(60deg, #b91c1c 25%, transparent 25.5%, transparent 75%, #b91c1c 75%); background-position: 0 0, 0 0, 30px 52px, 30px 52px, 0 0; background-size: 60px 105px; }
        .content { display: flex; align-items: center; flex-direction: column; color: #fff; text-align: center; transform: translateY(-2vh); }
        .mark { width: clamp(130px, 20vw, 250px); height: auto; margin-bottom: 1rem; filter: drop-shadow(0 10px 12px rgb(127 29 29 / .18)); }
        h1 { margin: 0; font-size: clamp(4rem, 12vw, 10rem); font-weight: 600; letter-spacing: -.075em; line-height: .9; }
        @media (max-width: 600px) { h1 { font-size: clamp(3.5rem, 18vw, 6rem); } }
    </style>
</head>
<body>
    <main class="splash" aria-label="Laravel">
        <div class="pattern" aria-hidden="true"></div>
        <nav class="auth-links" aria-label="Authentication">
            <a class="auth-button" href="{{ route('login') }}">Login</a>
            <a class="auth-button register-button" href="{{ route('register') }}">Register</a>
        </nav>
        <div class="content">
            <svg class="mark" viewBox="0 0 240 210" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Laravel logo">
                <path d="M120 8 205 57v98l-85 49-85-49V57L120 8Z" fill="none" stroke="currentColor" stroke-width="9" stroke-linejoin="round" />
                <path d="M35 57 120 106l85-49M120 106v98M120 106l48-28v58l-48 28-48-28V78l48 28Z" fill="none" stroke="currentColor" stroke-width="9" stroke-linejoin="round" />
            </svg>
            <h1>Laravel</h1>
        </div>
    </main>
</body>
</html>
