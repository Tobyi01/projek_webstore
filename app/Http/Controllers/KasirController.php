<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CashierShift;
use App\Models\Sale;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class KasirController extends Controller
{
    public function index(): View
    {
        $products = Product::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'price', 'stock', 'image']);

        $productData = $products->map(fn (Product $product) => [
            'id' => $product->id,
            'name' => $product->name,
            'price' => (float) $product->price,
            'stock' => $product->stock,
            'image' => $product->image ? Storage::url($product->image) : null,
            'code' => 'BRG'.str_pad((string) $product->id, 3, '0', STR_PAD_LEFT),
            'barcode' => '899'.str_pad((string) $product->id, 9, '0', STR_PAD_LEFT),
            'offline_token' => hash_hmac(
                'sha256',
                json_encode([
                    auth()->id(),
                    $product->id,
                    $product->name,
                    (int) round((float) $product->price * 100),
                    $product->stock,
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                config('app.key')
            ),
        ])->values();

        $currentShift = CashierShift::query()
            ->where('user_id', Auth::id())
            ->whereNull('closed_at')
            ->first();
        $shiftCashSales = $currentShift
            ? (float) Sale::query()
                ->where('cashier_shift_id', $currentShift->id)
                ->where('payment_method', 'Tunai')
                ->sum('total')
            : 0;
        $shiftTransactionCount = $currentShift
            ? Sale::query()->where('cashier_shift_id', $currentShift->id)->count()
            : 0;

        return view('kasir.index', compact('productData', 'currentShift', 'shiftCashSales', 'shiftTransactionCount'));
    }
}