<?php

namespace Tests\Feature;

use App\Models\CashierShift;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCashierShiftTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_cashier_shift_history_and_totals(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $cashier = User::factory()->create([
            'name' => 'Kasir Shift',
            'email' => 'shift@example.test',
            'role' => 'kasir',
        ]);
        $shift = CashierShift::query()->create([
            'user_id' => $cashier->id,
            'opening_cash' => 100000,
            'expected_cash' => 125000,
            'counted_cash' => 125000,
            'cash_difference' => 0,
            'opened_at' => now()->subHour(),
            'closed_at' => now(),
        ]);
        Sale::query()->create([
            'transaction_number' => 'TRX-ADMIN-SHIFT',
            'user_id' => $cashier->id,
            'cashier_shift_id' => $shift->id,
            'customer' => 'Umum',
            'payment_method' => 'Tunai',
            'subtotal' => 25000,
            'discount_percent' => 0,
            'discount_amount' => 0,
            'tax' => 0,
            'other_fee' => 0,
            'total' => 25000,
            'paid' => 25000,
            'change' => 0,
            'sold_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.cashier-shifts.index'))
            ->assertOk()
            ->assertSee('Riwayat Shift Kasir')
            ->assertSee('Kasir Shift')
            ->assertSee('shift@example.test')
            ->assertSee('Rp 100.000')
            ->assertSee('Rp 25.000')
            ->assertSee('Rp 125.000')
            ->assertSee('Ditutup');
    }

    public function test_cashier_cannot_view_admin_shift_history(): void
    {
        $cashier = User::factory()->create(['role' => 'kasir']);

        $this->actingAs($cashier)
            ->get(route('admin.cashier-shifts.index'))
            ->assertForbidden();
    }
}
