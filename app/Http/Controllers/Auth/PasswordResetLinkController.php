<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = strtolower(trim($request->email));
        $user = User::whereRaw('LOWER(email) = ?', [$email])->first();

        if (! $user) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => __('Email tidak terdaftar pada sistem.')]);
        }

        if (! $user->is_active) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => __('Akun Anda sedang tidak aktif. Hubungi admin untuk bantuan.')]);
        }

        $status = Password::sendResetLink(
            ['email' => $email]
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __('Link reset kata sandi telah dikirim ke email Anda.'))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }
}
