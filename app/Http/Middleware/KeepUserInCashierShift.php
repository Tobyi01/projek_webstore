<?php

namespace App\Http\Middleware;

use App\Models\CashierShift;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class KeepUserInCashierShift
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user || $user->role !== 'kasir') {
            return $next($request);
        }

        $hasOpenShift = CashierShift::query()
            ->where('user_id', $user->id)
            ->whereNull('closed_at')
            ->exists();
        $request->attributes->set('has_open_cashier_shift', $hasOpenShift);

        if (! $hasOpenShift || ! $request->routeIs('logout')) {
            return $next($request);
        }

        return redirect()
            ->route('kasir.index')
            ->with('shift_warning', 'Shift masih terbuka. Tutup shift terlebih dahulu sebelum keluar dari akun kasir.');
    }
}
