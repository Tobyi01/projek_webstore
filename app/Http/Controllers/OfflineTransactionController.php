<?php

namespace App\Http\Controllers;

use App\Events\OrderPlaced;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OfflineTransactionController extends Controller
{
    public function sync(Request $request): JsonResponse
    {
        $existing = Sale::query()
            ->where('transaction_number', $request->input('transaction_number'))
            ->first();

        if ($existing) {
            abort_unless($existing->user_id === $request->user()->id, 409);

            return response()->json([
                'success' => true,
                'already_synced' => true,
                'transaction_number' => $existing->transaction_number,
                'has_stock_conflicts' => ! empty($existing->stock_conflicts),
            ]);
        }

        $data = $request->validate([
            'transaction_number' => ['required', 'string', 'max:60'],
            'customer' => ['required', 'string', 'max:120'],
            'payment_method' => ['required', 'in:Tunai,QRIS,Debit,Kredit,E-Wallet,Transfer'],
            'paid' => ['required', 'numeric', 'min:0'],
            'sold_at' => ['required', 'date'],
            'items' => ['required', 'array', 'min:1', 'max:100'],
            'items.*.product_id' => ['required', 'integer'],
            'items.*.product_name' => ['required', 'string', 'max:255'],
            'items.*.qty' => ['required', 'integer', 'min:1', 'max:999'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'items.*.stock' => ['required', 'integer', 'min:0'],
            'items.*.offline_token' => ['required', 'string', 'size:64'],
        ]);

        foreach ($data['items'] as $item) {
            $expectedToken = hash_hmac(
                'sha256',
                json_encode([
                    $request->user()->id,
                    $item['product_id'],
                    $item['product_name'],
                    (int) round((float) $item['unit_price'] * 100),
                    $item['stock'],
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                config('app.key')
            );

            if (! hash_equals($expectedToken, $item['offline_token'])) {
                throw ValidationException::withMessages([
                    'items' => 'Data produk offline tidak cocok dengan katalog yang tersimpan.',
                ]);
            }
        }

        $quantities = collect($data['items'])
            ->groupBy('product_id')
            ->map(fn ($items): int => (int) $items->sum('qty'));
        $catalogStocks = collect($data['items'])
            ->groupBy('product_id')
            ->map(fn ($items): int => (int) $items->first()['stock']);

        foreach ($quantities as $productId => $quantity) {
            if ($quantity > $catalogStocks->get($productId, 0)) {
                throw ValidationException::withMessages([
                    'items' => 'Jumlah barang melebihi stok yang tersimpan di perangkat.',
                ]);
            }
        }

        $sale = DB::transaction(function () use ($data, $quantities, $request): Sale {
            $products = Product::query()
                ->whereIn('id', $quantities->keys())
                ->lockForUpdate()
                ->get()
                ->keyBy('id');
            $subtotal = round(collect($data['items'])->sum(
                fn (array $item): float => (float) $item['unit_price'] * (int) $item['qty']
            ), 2);
            $paid = round((float) $data['paid'], 2);

            if ($data['payment_method'] === 'Tunai' && $paid < $subtotal) {
                throw ValidationException::withMessages(['paid' => 'Jumlah pembayaran kurang.']);
            }

            if ($data['payment_method'] !== 'Tunai' && $paid !== $subtotal) {
                throw ValidationException::withMessages([
                    'paid' => 'Pembayaran non-tunai harus sesuai dengan total transaksi.',
                ]);
            }

            $conflicts = [];
            foreach ($quantities as $productId => $quantity) {
                $product = $products->get((int) $productId);

                if (! $product || ! $product->is_active || $product->stock < $quantity) {
                    $conflicts[] = [
                        'product_id' => (int) $productId,
                        'product_name' => $product?->name ?? collect($data['items'])->firstWhere('product_id', (int) $productId)['product_name'],
                        'sold_quantity' => $quantity,
                        'available_stock' => $product?->stock ?? 0,
                        'reason' => ! $product ? 'Produk sudah dihapus' : (
                            ! $product->is_active ? 'Produk sudah nonaktif' : 'Stok berubah sejak perangkat offline'
                        ),
                    ];
                }
            }

            $sale = Sale::query()->create([
                'transaction_number' => $data['transaction_number'],
                'user_id' => $request->user()->id,
                'customer' => $data['customer'],
                'payment_method' => $data['payment_method'],
                'subtotal' => $subtotal,
                'discount_percent' => 0,
                'discount_amount' => 0,
                'tax' => 0,
                'other_fee' => 0,
                'total' => $subtotal,
                'paid' => $paid,
                'change' => $data['payment_method'] === 'Tunai' ? round($paid - $subtotal, 2) : 0,
                'sold_at' => $data['sold_at'],
                'is_offline' => true,
                'stock_conflicts' => $conflicts ?: null,
            ]);

            foreach ($data['items'] as $item) {
                $product = $products->get((int) $item['product_id']);
                $lineTotal = round((float) $item['unit_price'] * (int) $item['qty'], 2);
                $sale->items()->create([
                    'product_id' => $product?->id,
                    'product_name' => $item['product_name'],
                    'quantity' => $item['qty'],
                    'unit_price' => $item['unit_price'],
                    'line_total' => $lineTotal,
                ]);
            }

            foreach ($quantities as $productId => $quantity) {
                $product = $products->get((int) $productId);
                if ($product) {
                    $product->stock = max(0, $product->stock - $quantity);
                    $product->save();
                }
            }

            return $sale->load('items');
        });

        event(new OrderPlaced($sale, $request->user()));

        return response()->json([
            'success' => true,
            'already_synced' => false,
            'transaction_number' => $sale->transaction_number,
            'has_stock_conflicts' => ! empty($sale->stock_conflicts),
        ]);
    }
}
