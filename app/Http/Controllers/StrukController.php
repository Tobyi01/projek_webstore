<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Services\ReceiptPrinter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class StrukController extends Controller
{
    public function cetak(Request $request, ReceiptPrinter $receiptPrinter): JsonResponse
    {
        $data = $request->validate([
            'transaction_number' => ['required', 'string', 'max:60', 'unique:sales,transaction_number'],
            'customer' => ['required', 'string', 'max:120'],
            'payment_method' => ['required', 'in:Tunai,QRIS,Debit,Kredit,E-Wallet,Transfer'],
            'discount_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'discount_amount' => ['nullable', 'numeric', 'min:0'],
            'tax' => ['nullable', 'numeric', 'min:0'],
            'other_fee' => ['nullable', 'numeric', 'min:0'],
            'paid' => ['required', 'numeric', 'min:0'],
            'items' => ['required', 'array', 'min:1', 'max:100'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.qty' => ['required', 'integer', 'min:1', 'max:999'],
        ]);

        $quantities = collect($data['items'])
            ->groupBy('product_id')
            ->map(fn ($items): int => (int) $items->sum('qty'));

        $sale = DB::transaction(function () use ($data, $quantities, $request): Sale {
            $products = Product::query()
                ->whereIn('id', $quantities->keys())
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $saleItems = [];
            $subtotal = 0;

            foreach ($quantities as $productId => $quantity) {
                $product = $products->get((int) $productId);

                if (!$product || !$product->is_active || $product->stock < $quantity) {
                    throw ValidationException::withMessages([
                        'items' => 'Produk tidak aktif atau stok tidak mencukupi: ' . ($product?->name ?? 'produk tidak ditemukan'),
                    ]);
                }

                $unitPrice = (float) $product->price;
                $lineTotal = round($unitPrice * $quantity, 2);
                $subtotal += $lineTotal;
                $saleItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'line_total' => $lineTotal,
                ];
            }

            $subtotal = round($subtotal, 2);
            $discountPercent = (float) ($data['discount_percent'] ?? 0);
            $percentDiscount = round($subtotal * $discountPercent / 100, 2);
            $amountDiscount = (float) ($data['discount_amount'] ?? 0);
            $tax = (float) ($data['tax'] ?? 0);
            $otherFee = (float) ($data['other_fee'] ?? 0);
            $total = round(max(0, $subtotal - $percentDiscount - $amountDiscount + $tax + $otherFee), 2);
            $paid = round((float) $data['paid'], 2);

            if ($paid < $total) {
                throw ValidationException::withMessages(['paid' => 'Jumlah pembayaran kurang.']);
            }

            $sale = Sale::create([
                'transaction_number' => $data['transaction_number'],
                'user_id' => $request->user()->id,
                'customer' => $data['customer'],
                'payment_method' => $data['payment_method'],
                'subtotal' => $subtotal,
                'discount_percent' => $discountPercent,
                'discount_amount' => $amountDiscount,
                'tax' => $tax,
                'other_fee' => $otherFee,
                'total' => $total,
                'paid' => $paid,
                'change' => round($paid - $total, 2),
                'sold_at' => now(),
            ]);

            foreach ($saleItems as $item) {
                $sale->items()->create([
                    'product_id' => $item['product']->id,
                    'product_name' => $item['product']->name,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'line_total' => $item['line_total'],
                ]);
                $item['product']->decrement('stock', $item['quantity']);
            }

            return $sale->load('items');
        });

        try {
            $receiptPrinter->print($sale);

            return response()->json([
                'success' => true,
                'transaction_number' => $sale->transaction_number,
                'message' => 'Transaksi tersimpan dan struk berhasil dicetak.',
            ]);
        } catch (\Throwable $exception) {
            Log::error('Receipt printing failed after saving sale.', [
                'transaction_number' => $sale->transaction_number,
                'message' => $exception->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'transaction_saved' => true,
                'message' => 'Transaksi tersimpan, tetapi printer kasir tidak dapat mencetak struk.',
            ], 503);
        }
    }
}