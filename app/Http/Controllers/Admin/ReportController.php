<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        $now = now();
        $monthStart = $now->copy()->startOfMonth();
        $monthEnd = $now->copy()->endOfDay();

        $salesThisMonth = Sale::query()
            ->whereBetween('sold_at', [$monthStart, $monthEnd])
            ->get();

        $topProducts = SaleItem::query()
            ->select('product_name', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(line_total) as total_sales'))
            ->groupBy('product_name')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        return view('admin.reports.index', [
            'monthRevenue' => (float) $salesThisMonth->sum('total'),
            'monthTransactions' => $salesThisMonth->count(),
            'totalProducts' => Product::query()->count(),
            'lowStockProducts' => Product::query()->whereColumn('stock', '<=', 'minimum_stock')->orderBy('stock')->get(),
            'topProducts' => $topProducts,
            'suppliers' => Supplier::query()->withCount('products')->get(),
            'recentSales' => Sale::query()->with('user')->orderByDesc('sold_at')->limit(5)->get(),
        ]);
    }
}
