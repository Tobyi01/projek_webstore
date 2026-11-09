<?php

namespace App\Http\Controllers\Api;

use App\Events\OrderPlaced;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MobileStoreController extends Controller
{
    public function dashboard(Request $request): JsonResponse
    {
        $now = now();
        $user = $request->user();

        if ($user->role === 'admin') {
            $monthSales = Sale::query()
                ->whereBetween('sold_at', [$now->copy()->startOfMonth(), $now->copy()->endOfDay()]);

            return response()->json([
                'role' => 'admin',
                'metrics' => [
                    ['label' => 'Penjualan bulan ini', 'value' => (float) (clone $monthSales)->sum('total'), 'format' => 'currency'],
                    ['label' => 'Penjualan tahun ini', 'value' => (float) Sale::query()->whereBetween('sold_at', [$now->copy()->startOfYear(), $now->copy()->endOfDay()])->sum('total'), 'format' => 'currency'],
                    ['label' => 'Transaksi bulan ini', 'value' => (clone $monthSales)->count(), 'format' => 'number'],
                    ['label' => 'Stok menipis', 'value' => Product::query()->where('stock', '<=', 5)->count(), 'format' => 'products'],
                ],
                'low_stock' => Product::query()
                    ->where('stock', '<=', 5)
                    ->orderBy('stock')
                    ->limit(5)
                    ->get(['id', 'name', 'category', 'stock']),
                'recent_sales' => Sale::query()
                    ->with('user:id,name')
                    ->latest('sold_at')
                    ->limit(6)
                    ->get(['id', 'transaction_number', 'user_id', 'customer', 'payment_method', 'total', 'sold_at']),
            ]);
        }

        return response()->json([
            'role' => 'kasir',
            'metrics' => [
                ['label' => 'Nama barang', 'value' => Product::query()->where('is_active', true)->count(), 'format' => 'number'],
                ['label' => 'Stok barang', 'value' => (int) Product::query()->where('is_active', true)->sum('stock'), 'format' => 'number'],
                ['label' => 'Telah terjual', 'value' => (int) SaleItem::query()->sum('quantity'), 'format' => 'number'],
                ['label' => 'Kategori barang', 'value' => Product::query()->where('is_active', true)->whereNotNull('category')->where('category', '!=', '')->distinct()->count('category'), 'format' => 'number'],
            ],
        ]);
    }

    public function products(Request $request): JsonResponse
    {
        $products = Product::query()
            ->when($request->user()->role !== 'admin', fn ($query) => $query->where('is_active', true)->where('stock', '>', 0))
            ->orderBy('name')
            ->get(['id', 'name', 'category', 'description', 'price', 'stock', 'image', 'is_active'])
            ->map(fn (Product $product): array => [
                'id' => $product->id,
                'name' => $product->name,
                'category' => $product->category,
                'description' => $product->description,
                'price' => (float) $product->price,
                'stock' => $product->stock,
                'image_url' => $product->image ? $request->getSchemeAndHttpHost().'/storage/'.$product->image : null,
                'is_active' => $product->is_active,
            ]);

        return response()->json(['data' => $products]);
    }

    public function transactions(Request $request): JsonResponse
    {
        $filters = $request->validate([
            'period' => ['nullable', 'in:day,month,year'],
            'date' => ['nullable', 'date_format:Y-m-d'],
            'month' => ['nullable', 'date_format:Y-m'],
            'year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
        ]);
        $period = $filters['period'] ?? 'day';
        $reference = match ($period) {
            'month' => Carbon::createFromFormat('!Y-m', $filters['month'] ?? now()->format('Y-m')),
            'year' => Carbon::createFromFormat('!Y', (string) ($filters['year'] ?? now()->year)),
            default => Carbon::parse($filters['date'] ?? now()->toDateString()),
        };
        $start = match ($period) {
            'month' => $reference->copy()->startOfMonth(),
            'year' => $reference->copy()->startOfYear(),
            default => $reference->copy()->startOfDay(),
        };
        $end = match ($period) {
            'month' => $reference->copy()->endOfMonth(),
            'year' => $reference->copy()->endOfYear(),
            default => $reference->copy()->endOfDay(),
        };

        $sales = Sale::query()
            ->with('items:id,sale_id,product_name,quantity,unit_price,line_total')
            ->whereBetween('sold_at', [$start, $end])
            ->when($request->user()->role !== 'admin', fn ($query) => $query->where('user_id', $request->user()->id))
            ->orderByDesc('sold_at')
            ->get(['id', 'transaction_number', 'customer', 'payment_method', 'subtotal', 'discount_percent', 'discount_amount', 'tax', 'other_fee', 'total', 'paid', 'change', 'sold_at']);

        return response()->json([
            'period' => $period,
            'start' => $start->toDateString(),
            'count' => $sales->count(),
            'items_sold' => $sales->sum(fn (Sale $sale): int => (int) $sale->items->sum('quantity')),
            'revenue' => (float) $sales->sum('total'),
            'data' => $sales,
        ]);
    }

    public function storeTransaction(Request $request): JsonResponse
    {
        $data = $request->validate([
            'customer' => ['required', 'string', 'max:120'],
            'payment_method' => ['required', 'in:Tunai,QRIS,Debit,Kredit,E-Wallet,Transfer'],
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
            $subtotal = 0;
            $items = [];

            foreach ($quantities as $productId => $quantity) {
                $product = $products->get((int) $productId);
                if (! $product || ! $product->is_active || $product->stock < $quantity) {
                    abort(422, 'Produk tidak aktif atau stok tidak mencukupi: '.($product?->name ?? 'produk tidak ditemukan'));
                }

                $unitPrice = (float) $product->price;
                $lineTotal = round($unitPrice * $quantity, 2);
                $subtotal += $lineTotal;
                $items[] = compact('product', 'quantity', 'unitPrice', 'lineTotal');
            }

            $subtotal = round($subtotal, 2);
            $paid = round((float) $data['paid'], 2);
            if ($data['payment_method'] === 'Tunai' && $paid < $subtotal) {
                abort(422, 'Jumlah pembayaran kurang.');
            }
            if ($data['payment_method'] !== 'Tunai' && $paid !== $subtotal) {
                abort(422, 'Pembayaran non-tunai harus sesuai dengan total transaksi.');
            }

            $sale = Sale::query()->create([
                'transaction_number' => 'NS-'.now()->format('YmdHis').'-'.strtoupper(Str::random(5)),
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
                'sold_at' => now(),
            ]);

            foreach ($items as $item) {
                $sale->items()->create([
                    'product_id' => $item['product']->id,
                    'product_name' => $item['product']->name,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unitPrice'],
                    'line_total' => $item['lineTotal'],
                ]);
                $item['product']->decrement('stock', $item['quantity']);
            }

            return $sale->load('items');
        });

        event(new OrderPlaced($sale, $request->user()));

        return response()->json([
            'message' => 'Transaksi berhasil disimpan.',
            'transaction_number' => $sale->transaction_number,
            'total' => (float) $sale->total,
            'paid' => (float) $sale->paid,
            'change' => (float) $sale->change,
            'payment_method' => $sale->payment_method,
        ], 201);
    }
}
