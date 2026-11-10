<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StockMovementController extends Controller
{
    public function index(): View
    {
        $products = Product::query()
            ->with('supplier')
            ->orderBy('name')
            ->get();

        return view('admin.stock.index', [
            'products' => $products,
            'lowStockProducts' => Product::query()->whereColumn('stock', '<=', 'minimum_stock')->orderBy('stock')->get(),
            'movements' => StockMovement::query()->with('product')->orderByDesc('created_at')->limit(20)->get(),
        ]);
    }

    public function adjust(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'type' => ['required', 'in:in,out,adjustment'],
            'quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        $product = Product::query()->findOrFail($data['product_id']);
        $delta = $data['quantity'];

        if ($data['type'] === 'out') {
            if ($product->stock < $data['quantity']) {
                return back()->withErrors(['quantity' => 'Jumlah stok keluar melebihi stok yang tersedia.'])->withInput();
            }
            $delta = -$data['quantity'];
        }

        if ($data['type'] === 'adjustment') {
            $delta = $data['quantity'] - $product->stock;
        }

        $product->stock = max(0, $product->stock + $delta);
        $product->save();

        StockMovement::query()->create([
            'product_id' => $product->id,
            'type' => $data['type'],
            'quantity' => $data['quantity'],
            'reference' => 'manual-adjustment',
            'notes' => $data['notes'] ?? 'Penyesuaian stok manual',
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('admin.stock.index')->with('success', 'Stok produk berhasil diperbarui.');
    }
}
