<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use App\Services\ReceiptPrinter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Mockery;
use Tests\TestCase;

class TransactionHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful_payment_saves_selected_items_and_decrements_stock(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(stock: 5, price: 3000);
        $printer = Mockery::mock(ReceiptPrinter::class);
        $printer->shouldReceive('print')->once();
        $this->instance(ReceiptPrinter::class, $printer);

        $response = $this->actingAs($user)->postJson(route('struk.cetak'), [
            'transaction_number' => 'TRX-TEST-001',
            'customer' => 'Umum',
            'payment_method' => 'Tunai',
            'paid' => 7000,
            'items' => [['product_id' => $product->id, 'qty' => 2]],
        ]);

        $response->assertOk()->assertJsonPath('success', true);
        $this->assertDatabaseHas('sales', [
            'transaction_number' => 'TRX-TEST-001',
            'subtotal' => 6000,
            'total' => 6000,
            'paid' => 7000,
            'change' => 1000,
        ]);
        $this->assertDatabaseHas('sale_items', [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'quantity' => 2,
            'unit_price' => 3000,
            'line_total' => 6000,
        ]);
        $this->assertSame(3, $product->fresh()->stock);
    }

    public function test_payment_below_total_is_not_saved_or_printed(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(stock: 5, price: 3000);
        $printer = Mockery::mock(ReceiptPrinter::class);
        $printer->shouldNotReceive('print');
        $this->instance(ReceiptPrinter::class, $printer);

        $this->actingAs($user)->postJson(route('struk.cetak'), [
            'transaction_number' => 'TRX-TEST-002',
            'customer' => 'Umum',
            'payment_method' => 'Tunai',
            'paid' => 2000,
            'items' => [['product_id' => $product->id, 'qty' => 1]],
        ])->assertUnprocessable();

        $this->assertDatabaseCount('sales', 0);
        $this->assertSame(5, $product->fresh()->stock);
    }

    public function test_history_can_filter_by_day_month_and_year(): void
    {
        Carbon::setTestNow('2026-09-29 12:00:00');
        $user = User::factory()->create();
        $this->createSale($user, 'TRX-DAY', '2026-09-29 10:00:00');
        $this->createSale($user, 'TRX-MONTH', '2026-09-12 10:00:00');
        $this->createSale($user, 'TRX-PREVIOUS-MONTH', '2026-08-21 10:00:00');
        $this->createSale($user, 'TRX-PREVIOUS-YEAR', '2025-12-15 10:00:00');

        $this->actingAs($user)
            ->get(route('transactions.index', ['period' => 'day', 'date' => '2026-09-29']))
            ->assertOk()
            ->assertViewHas('saleCount', 1)
            ->assertViewHas('groups', fn ($groups): bool => $groups->count() === 1);

        $this->get(route('transactions.index', ['period' => 'month', 'month' => '2026-09']))
            ->assertOk()
            ->assertViewHas('saleCount', 2)
            ->assertViewHas('groups', fn ($groups): bool => $groups->count() === 2);

        $this->get(route('transactions.index', ['period' => 'year', 'year' => 2026]))
            ->assertOk()
            ->assertViewHas('saleCount', 3)
            ->assertViewHas('groups', fn ($groups): bool => $groups->count() === 2);

        Carbon::setTestNow();
    }

    private function createProduct(int $stock, int $price): Product
    {
        return Product::create([
            'name' => 'Produk Uji',
            'category' => 'Uji',
            'price' => $price,
            'stock' => $stock,
            'is_active' => true,
        ]);
    }

    private function createSale(User $user, string $number, string $soldAt): Sale
    {
        $sale = Sale::create([
            'transaction_number' => $number,
            'user_id' => $user->id,
            'customer' => 'Umum',
            'payment_method' => 'Tunai',
            'subtotal' => 5000,
            'discount_percent' => 0,
            'discount_amount' => 0,
            'tax' => 0,
            'other_fee' => 0,
            'total' => 5000,
            'paid' => 5000,
            'change' => 0,
            'sold_at' => Carbon::parse($soldAt),
        ]);

        $sale->items()->create([
            'product_name' => 'Snapshot Produk',
            'quantity' => 1,
            'unit_price' => 5000,
            'line_total' => 5000,
        ]);

        return $sale;
    }
}