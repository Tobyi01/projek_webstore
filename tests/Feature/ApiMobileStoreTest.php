<?php

namespace Tests\Feature;

use App\Events\OrderPlaced;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ApiMobileStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_load_store_dashboard_and_products(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $product = Product::query()->create([
            'name' => 'Produk Mobile',
            'category' => 'Minuman',
            'price' => 12000,
            'stock' => 3,
            'is_active' => true,
        ]);
        $token = $admin->createToken('test')->plainTextToken;

        $this->withToken($token)
            ->getJson('/api/dashboard')
            ->assertOk()
            ->assertJsonPath('role', 'admin')
            ->assertJsonPath('metrics.3.value', 1)
            ->assertJsonPath('low_stock.0.name', $product->name);

        $this->withToken($token)
            ->getJson('/api/products')
            ->assertOk()
            ->assertJsonPath('data.0.name', $product->name)
            ->assertJsonPath('data.0.price', 12000);
    }

    public function test_cashier_can_save_a_sale_and_stock_is_decremented(): void
    {
        Event::fake([OrderPlaced::class]);
        $cashier = User::factory()->create(['role' => 'kasir']);
        $product = Product::query()->create([
            'name' => 'Produk Kasir',
            'category' => 'Makanan',
            'price' => 15000,
            'stock' => 5,
            'is_active' => true,
        ]);

        $response = $this->withToken($cashier->createToken('test')->plainTextToken)
            ->postJson('/api/transactions', [
                'customer' => 'Umum',
                'payment_method' => 'Tunai',
                'paid' => 40000,
                'items' => [
                    ['product_id' => $product->id, 'qty' => 2],
                ],
            ]);

        $response->assertCreated()
            ->assertJsonPath('total', 30000)
            ->assertJsonPath('change', 10000);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 3,
        ]);
        $this->assertDatabaseHas('sale_items', [
            'product_id' => $product->id,
            'quantity' => 2,
            'line_total' => 30000,
        ]);
        Event::assertDispatched(OrderPlaced::class);
    }

    public function test_cashier_cannot_sell_more_than_available_stock(): void
    {
        $cashier = User::factory()->create(['role' => 'kasir']);
        $product = Product::query()->create([
            'name' => 'Stok Terbatas',
            'price' => 5000,
            'stock' => 1,
            'is_active' => true,
        ]);

        $this->withToken($cashier->createToken('test')->plainTextToken)
            ->postJson('/api/transactions', [
                'customer' => 'Umum',
                'payment_method' => 'Tunai',
                'paid' => 10000,
                'items' => [
                    ['product_id' => $product->id, 'qty' => 2],
                ],
            ])
            ->assertUnprocessable();

        $this->assertDatabaseCount('sales', 0);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 1,
        ]);
    }

    public function test_deactivated_cashier_token_cannot_access_mobile_store_data(): void
    {
        $cashier = User::factory()->create(['role' => 'kasir']);
        $token = $cashier->createToken('test')->plainTextToken;
        $cashier->is_active = false;
        $cashier->save();

        $this->withToken($token)
            ->getJson('/api/products')
            ->assertForbidden()
            ->assertJsonPath(
                'message',
                'Akun Anda telah dinonaktifkan. Hubungi administrator.',
            );
    }
}
