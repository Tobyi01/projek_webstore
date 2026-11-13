<x-guest-layout>
    <style>
        .reset-page {
            min-height: 100vh;
            display: grid;
            place-items: center;
            background: #091b2d;
            color: #e6edf7;
            font-family: 'Segoe UI', sans-serif;
        }

        .reset-panel {
            width: min(100%, 34rem);
            padding: 2rem 2.2rem 2.4rem;
            border: 1px solid rgba(148, 163, 184, 0.22);
            border-radius: 0.9rem;
            background: rgba(15, 24, 36, 0.86);
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.22);
        }

        .brand-mark {
            display: grid;
            place-items: center;
            margin: 0 auto 1.4rem;
            width: 5.3rem;
            height: 5.3rem;
            border-radius: 1.1rem;
            color: #dfeaf8;
            border: 1px solid rgba(148, 163, 184, 0.32);
            background: rgba(255, 255, 255, 0.02);
        }

        .brand-mark svg {
            width: 3.2rem;
            height: 3.2rem;
            opacity: 0.95;
        }

        .heading {
            margin: 0 0 1.25rem;
            text-align: center;
            font-size: 1.8rem;
            font-weight: 700;
            color: #edf6ff;
        }

        .field {
            margin-top: 1rem;
        }

        .field label {
            display: block;
            margin-bottom: 0.45rem;
            color: #dbeafe;
            font-size: 0.92rem;
            font-weight: 700;
        }

        .field input {
            width: 100%;
            height: 3.2rem;
            border: 1px solid rgba(148, 163, 184, 0.28);
            border-radius: 0.6rem;
            background: rgba(9, 17, 24, 0.7);
            color: #edf6ff;
            padding: 0 0.9rem;
            font-size: 1rem;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .field input:focus {
            border-color: #56d9a9;
            box-shadow: 0 0 0 3px rgba(86, 217, 169, 0.12);
        }

        .error-box {
            margin-top: 0.8rem;
            color: #f6a7a7;
            font-size: 0.85rem;
        }

        .submit-btn {
            width: 100%;
            margin-top: 1.2rem;
            height: 3.2rem;
            border: 1px solid rgba(148, 163, 184, 0.35);
            border-radius: 0.6rem;
            background: #f3f4f6;
            color: #0f172a;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            cursor: pointer;
            transition: transform 0.2s ease, opacity 0.2s ease;
        }

        .submit-btn:hover {
            transform: translateY(-1px);
            opacity: 0.96;
        }

        .back-link {
            display: block;
            margin-top: 1rem;
            text-align: center;
            color: #dfeaf8;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>

    <div class="reset-page">
        <div class="reset-panel">
            <div class="brand-mark" aria-hidden="true">
                <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16 22L32 8L48 22V42L32 56L16 42V22Z" stroke="currentColor" stroke-width="2.4" stroke-linejoin="round"/>
                    <path d="M32 28L48 16V42L32 54L16 42V16L32 28Z" stroke="currentColor" stroke-width="2.4" stroke-linejoin="round"/>
                    <path d="M32 8V56" stroke="currentColor" stroke-width="2.4"/>
                </svg>
            </div>

            <h1 class="heading">Buat Kata Sandi Baru</h1>

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autocomplete="username">
                    @error('email')
                        <div class="error-box">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label for="password">Kata Sandi Baru</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password">
                    @error('password')
                        <div class="error-box">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
                    @error('password_confirmation')
                        <div class="error-box">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="submit-btn">Reset Kata Sandi</button>
            </form>

            <a href="{{ route('login') }}" class="back-link">Kembali ke Login</a>
        </div>
    </div>
</x-guest-layout>
