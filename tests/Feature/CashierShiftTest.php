<?php

namespace Tests\Feature;

use App\Models\CashierShift;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CashierShiftTest extends TestCase
{
    use RefreshDatabase;

    public function test_cashier_must_open_a_shift_before_using_the_pos(): void
    {
        $cashier = User::factory()->create(['role' => 'kasir']);

        $this->actingAs($cashier)
            ->get(route('kasir.index'))
            ->assertOk()
            ->assertSee('Mulai shift kasir')
            ->assertSee('name="opening_cash"', false)
            ->assertSee('<fieldset class="pos-grid" disabled>', false);
    }

    public function test_cashier_can_open_only_one_shift_at_a_time(): void
    {
        $cashier = User::factory()->create(['role' => 'kasir']);

        $this->actingAs($cashier)
            ->post(route('kasir.shift.start'), ['opening_cash' => 100000])
            ->assertRedirect(route('kasir.index'))
            ->assertSessionHas('status');

        $this->assertDatabaseHas('cashier_shifts', [
            'user_id' => $cashier->id,
            'opening_cash' => 100000,
            'closed_at' => null,
        ]);

        $this->post(route('kasir.shift.start'), ['opening_cash' => 50000])
            ->assertSessionHasErrors('opening_cash');

        $this->assertDatabaseCount('cashier_shifts', 1);
    }

    public function test_closing_shift_automatically_sets_cash_to_opening_amount_plus_cash_sales(): void
    {
        $cashier = User::factory()->create(['role' => 'kasir']);
        $shift = CashierShift::query()->create([
            'user_id' => $cashier->id,
            'opening_cash' => 100000,
            'opened_at' => now()->subHours(4),
        ]);

        $this->createSale($cashier, $shift, 'TRX-SHIFT-CASH', 'Tunai', 25000);
        $this->createSale($cashier, $shift, 'TRX-SHIFT-CARD', 'Debit', 70000);

        $this->actingAs($cashier)
            ->post(route('kasir.shift.close', $shift))
            ->assertRedirect(route('kasir.index'))
            ->assertSessionHas('status', 'Shift ditutup otomatis. Kas akhir disesuaikan menjadi Rp 125.000 (modal awal + penjualan tunai).');

        $this->assertDatabaseHas('cashier_shifts', [
            'id' => $shift->id,
            'expected_cash' => 125000,
            'counted_cash' => 125000,
            'cash_difference' => 0,
        ]);
        $this->assertNotNull($shift->fresh()->closed_at);
    }

    public function test_closing_shift_uses_zero_difference_when_there_are_no_cash_sales(): void
    {
        $cashier = User::factory()->create(['role' => 'kasir']);
        $shift = CashierShift::query()->create([
            'user_id' => $cashier->id,
            'opening_cash' => 80000,
            'opened_at' => now()->subHours(2),
        ]);
        $this->createSale($cashier, $shift, 'TRX-SHIFT-ONLY-NONCASH', 'Debit', 25000);

        $this->actingAs($cashier)
            ->post(route('kasir.shift.close', $shift))
            ->assertRedirect(route('kasir.index'));

        $this->assertDatabaseHas('cashier_shifts', [
            'id' => $shift->id,
            'expected_cash' => 80000,
            'counted_cash' => 80000,
            'cash_difference' => 0,
        ]);
    }

    public function test_cashier_cannot_close_another_users_shift(): void
    {
        $cashier = User::factory()->create(['role' => 'kasir']);
        $otherCashier = User::factory()->create(['role' => 'kasir']);
        $shift = CashierShift::query()->create([
            'user_id' => $otherCashier->id,
            'opening_cash' => 100000,
            'opened_at' => now(),
        ]);

        $this->actingAs($cashier)
            ->post(route('kasir.shift.close', $shift))
            ->assertNotFound();

        $this->assertNull($shift->fresh()->closed_at);
    }

    public function test_sale_cannot_be_saved_without_an_open_shift(): void
    {
        $cashier = User::factory()->create(['role' => 'kasir']);
        $product = Product::query()->create([
            'name' => 'Produk Shift',
            'price' => 3000,
            'stock' => 2,
            'is_active' => true,
        ]);

        $this->actingAs($cashier)
            ->postJson(route('struk.cetak'), [
                'transaction_number' => 'TRX-NO-SHIFT',
                'customer' => 'Umum',
                'payment_method' => 'Tunai',
                'paid' => 3000,
                'items' => [['product_id' => $product->id, 'qty' => 1]],
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('shift');

        $this->assertDatabaseCount('sales', 0);
        $this->assertSame(2, $product->fresh()->stock);
    }

    public function test_cashier_can_use_other_cashier_features_but_cannot_log_out_until_shift_is_closed(): void
    {
        $cashier = User::factory()->create(['role' => 'kasir']);
        $shift = CashierShift::query()->create([
            'user_id' => $cashier->id,
            'opening_cash' => 50000,
            'opened_at' => now(),
        ]);

        $this->actingAs($cashier)
            ->get(route('transactions.index'))
            ->assertOk();

        $this->get(route('dashboard'))->assertOk();
        $this->get(route('products.index'))->assertOk();
        $this->get(route('profile.edit'))->assertOk();

        $this->post(route('logout'))
            ->assertRedirect(route('kasir.index'))
            ->assertSessionHas('shift_warning');

        $this->assertAuthenticatedAs($cashier);

        $this->post(route('kasir.shift.close', $shift))
            ->assertRedirect(route('kasir.index'));

        $this->post(route('logout'))->assertRedirect('/');
        $this->assertGuest();
    }

    private function createSale(User $user, CashierShift $shift, string $number, string $method, int $total): Sale
    {
        return Sale::query()->create([
            'transaction_number' => $number,
            'user_id' => $user->id,
            'cashier_shift_id' => $shift->id,
            'customer' => 'Umum',
            'payment_method' => $method,
            'subtotal' => $total,
            'discount_percent' => 0,
            'discount_amount' => 0,
            'tax' => 0,
            'other_fee' => 0,
            'total' => $total,
            'paid' => $total,
            'change' => 0,
            'sold_at' => now(),
        ]);
    }
}
