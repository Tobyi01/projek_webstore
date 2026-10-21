<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CashierController extends Controller
{
    public function index(): View
    {
        return view('admin.cashiers.index', [
            'cashiers' => User::query()
                ->where('role', 'kasir')
                ->orderBy('name')
                ->paginate(15),
        ]);
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        abort_unless($user->role === 'kasir', 404);

        $user->is_active = ! $user->is_active;
        $user->save();

        $message = $user->is_active
            ? 'Akun kasir berhasil diaktifkan.'
            : 'Akun kasir berhasil dinonaktifkan.';

        return redirect()->route('admin.cashiers.index')->with('status', $message);
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_unless($user->role === 'kasir', 404);

        $user->delete();

        return redirect()->route('admin.cashiers.index')->with('status', 'Akun kasir berhasil dihapus.');
    }
}
