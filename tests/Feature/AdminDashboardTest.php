<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_displays_store_metrics_without_a_cashier_link(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Product::create([
            'name' => 'Kopi Uji',
            'category' => 'Minuman',
            'price' => 14000,
            'stock' => 3,
            'is_active' => true,
        ]);
        Sale::create([
            'transaction_number' => 'TRX-ADMIN-001',
            'user_id' => $admin->id,
            'customer' => 'Umum',
            'payment_method' => 'Tunai',
            'subtotal' => 42000,
            'discount_percent' => 0,
            'discount_amount' => 0,
            'tax' => 0,
            'other_fee' => 0,
            'total' => 42000,
            'paid' => 42000,
            'change' => 0,
            'sold_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('PENJUALAN BULAN INI')
            ->assertSee('Rp 42.000')
            ->assertSee('Kopi Uji')
            ->assertSee('Tunai')
            ->assertSee(route('transactions.index'), false)
            ->assertDontSee(route('kasir.index'), false);
    }

    public function test_cashier_dashboard_keeps_the_transaction_shortcut(): void
    {
        $cashier = User::factory()->create(['role' => 'kasir']);

        $this->actingAs($cashier)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Mulai transaksi')
            ->assertSee(route('kasir.index'), false)
            ->assertSee(route('products.index'), false);
    }

    public function test_cashier_cannot_open_product_creation_form(): void
    {
        $cashier = User::factory()->create(['role' => 'kasir']);

        $this->actingAs($cashier)
            ->get(route('products.create'))
            ->assertForbidden()
            ->assertSee('Anda tidak memiliki izin untuk membuka halaman ini.');
    }
}
