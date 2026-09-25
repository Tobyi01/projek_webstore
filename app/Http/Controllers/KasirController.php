<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class KasirController extends Controller
{
    public function index(): View
    {
        $products = Product::query()
            ->where('is_active', true)
            ->where('stock', '>', 0)
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
        ])->values();

        return view('kasir.index', compact('productData'));
    }
}