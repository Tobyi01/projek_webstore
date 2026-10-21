<?php

namespace Tests\Feature;

use App\Models\Sale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCashierManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_registered_cashiers_and_their_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $activeCashier = User::factory()->create([
            'name' => 'Kasir Aktif',
            'role' => 'kasir',
            'is_active' => true,
        ]);
        $inactiveCashier = User::factory()->create([
            'name' => 'Kasir Nonaktif',
            'role' => 'kasir',
            'is_active' => false,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.cashiers.index'))
            ->assertOk()
            ->assertSee($activeCashier->name)
            ->assertSee($activeCashier->email)
            ->assertSee($inactiveCashier->name)
            ->assertSee('Nonaktifkan')
            ->assertSee('Aktifkan');
    }

    public function test_admin_can_disable_and_reenable_a_cashier_and_disabled_cashier_loses_access(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $cashier = User::factory()->create(['role' => 'kasir']);

        $this->actingAs($admin)
            ->patch(route('admin.cashiers.status', $cashier))
            ->assertRedirect(route('admin.cashiers.index'));

        $this->assertDatabaseHas('users', ['id' => $cashier->id, 'is_active' => false]);

        $cashier->refresh();
        $this->actingAs($cashier)
            ->get(route('dashboard'))
            ->assertRedirect(route('login'));

        $this->assertGuest();

        $this->actingAs($admin)
            ->patch(route('admin.cashiers.status', $cashier))
            ->assertRedirect(route('admin.cashiers.index'));

        $this->assertDatabaseHas('users', ['id' => $cashier->id, 'is_active' => true]);
    }

    public function test_disabled_cashier_cannot_log_in(): void
    {
        $cashier = User::factory()->create([
            'role' => 'kasir',
            'is_active' => false,
        ]);

        $this->post(route('login'), [
            'email' => $cashier->email,
            'password' => 'password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_admin_can_delete_cashier_without_deleting_sales_history(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $cashier = User::factory()->create(['role' => 'kasir']);
        $sale = Sale::create([
            'transaction_number' => 'TRX-CASHIER-DELETE-001',
            'user_id' => $cashier->id,
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
            ->delete(route('admin.cashiers.destroy', $cashier))
            ->assertRedirect(route('admin.cashiers.index'));

        $this->assertDatabaseMissing('users', ['id' => $cashier->id]);
        $this->assertDatabaseHas('sales', ['id' => $sale->id, 'user_id' => null]);
    }

    public function test_cashier_cannot_access_cashier_management(): void
    {
        $cashier = User::factory()->create(['role' => 'kasir']);
        $anotherCashier = User::factory()->create(['role' => 'kasir']);

        $this->actingAs($cashier)
            ->get(route('admin.cashiers.index'))
            ->assertForbidden();

        $this->actingAs($cashier)
            ->patch(route('admin.cashiers.status', $anotherCashier))
            ->assertForbidden();
    }
}
