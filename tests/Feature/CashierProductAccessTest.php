<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CashierProductAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_cashier_can_view_product_list_and_details_without_management_actions(): void
    {
        $cashier = User::factory()->create(['role' => 'kasir']);
        $product = Product::create([
            'name' => 'Produk Tampilan Kasir',
            'category' => 'Minuman',
            'description' => 'Produk untuk uji akses baca.',
            'price' => 12500,
            'stock' => 8,
            'is_active' => true,
        ]);

        $this->actingAs($cashier)
            ->get(route('products.index'))
            ->assertOk()
            ->assertSee($product->name)
            ->assertSee(route('products.show', $product), false)
            ->assertSee(route('kasir.index'), false)
            ->assertDontSee(route('products.create'), false)
            ->assertDontSee(route('products.edit', $product), false)
            ->assertDontSee('Tambah Product')
            ->assertDontSee('Edit')
            ->assertDontSee('Hapus');

        $this->actingAs($cashier)
            ->get(route('products.show', $product))
            ->assertOk()
            ->assertSee($product->name)
            ->assertDontSee(route('products.edit', $product), false);
    }

    public function test_cashier_cannot_create_update_or_delete_products(): void
    {
        $cashier = User::factory()->create(['role' => 'kasir']);
        $product = Product::create([
            'name' => 'Produk Hanya Admin',
            'category' => 'Makanan',
            'price' => 10000,
            'stock' => 2,
            'is_active' => true,
        ]);

        $this->actingAs($cashier)
            ->get(route('products.create'))
            ->assertForbidden();

        $this->actingAs($cashier)
            ->post(route('products.store'), [
                'name' => 'Produk Baru',
                'price' => 1000,
                'stock' => 1,
            ])
            ->assertForbidden();

        $this->actingAs($cashier)
            ->put(route('products.update', $product), [
                'name' => 'Produk Diubah',
                'price' => 2000,
                'stock' => 3,
            ])
            ->assertForbidden();

        $this->actingAs($cashier)
            ->delete(route('products.destroy', $product))
            ->assertForbidden();

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Produk Hanya Admin',
        ]);
    }
}
