<?php

namespace App\Http\Controllers;

use App\Models\CashierShift;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CashierShiftController extends Controller
{
    public function start(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'opening_cash' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
        ]);

        DB::transaction(function () use ($request, $data): void {
            $user = User::query()->lockForUpdate()->findOrFail($request->user()->id);
            $hasOpenShift = CashierShift::query()
                ->where('user_id', $user->id)
                ->whereNull('closed_at')
                ->lockForUpdate()
                ->exists();

            if ($hasOpenShift) {
                throw ValidationException::withMessages([
                    'opening_cash' => 'Masih ada shift yang belum ditutup.',
                ]);
            }

            CashierShift::query()->create([
                'user_id' => $user->id,
                'opening_cash' => $data['opening_cash'],
                'opened_at' => now(),
            ]);
        });

        return redirect()->route('kasir.index')->with('status', 'Shift berhasil dibuka. Selamat bekerja!');
    }

    public function close(Request $request, CashierShift $shift): RedirectResponse
    {
        abort_unless($shift->user_id === $request->user()->id, 404);

        $result = DB::transaction(function () use ($request, $shift): array {
            $lockedShift = CashierShift::query()
                ->whereKey($shift->id)
                ->where('user_id', $request->user()->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedShift->closed_at !== null) {
                throw ValidationException::withMessages([
                    'shift' => 'Shift ini sudah ditutup.',
                ]);
            }

            $cashSales = (float) $lockedShift->sales()
                ->where('payment_method', 'Tunai')
                ->sum('total');
            $expectedCash = round((float) $lockedShift->opening_cash + $cashSales, 2);

            $lockedShift->update([
                'expected_cash' => $expectedCash,
                'counted_cash' => $expectedCash,
                'cash_difference' => 0,
                'closed_at' => now(),
            ]);

            return ['expectedCash' => $expectedCash];
        });

        $message = sprintf(
            'Shift ditutup otomatis. Kas akhir disesuaikan menjadi Rp %s (modal awal + penjualan tunai).',
            number_format($result['expectedCash'], 0, ',', '.')
        );

        return redirect()->route('kasir.index')->with('status', $message);
    }
}
