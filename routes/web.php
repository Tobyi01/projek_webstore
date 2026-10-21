<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StrukController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\TransactionHistoryController;
use App\Http\Controllers\Admin\CashierController;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\Auth;

$adminDashboard = function () {
    $now = now();
    $monthStart = $now->copy()->startOfMonth();
    $monthEnd = $now->copy()->endOfDay();
    $trendStart = $monthStart->copy()->subMonths(5);
    $trendSales = Sale::query()
        ->whereBetween('sold_at', [$trendStart, $monthEnd])
        ->get(['sold_at', 'total', 'payment_method']);
    $salesByMonth = $trendSales->groupBy(fn (Sale $sale): string => $sale->sold_at->format('Y-m'));
    $monthlySales = collect(range(5, 0))->map(function (int $monthsAgo) use ($now, $salesByMonth): array {
        $month = $now->copy()->startOfMonth()->subMonths($monthsAgo);

        return [
            'label' => $month->locale('id')->translatedFormat('M'),
            'revenue' => (float) $salesByMonth->get($month->format('Y-m'), collect())->sum('total'),
        ];
    });
    $monthSales = $salesByMonth->get($now->format('Y-m'), collect());
    $paymentMethods = $monthSales
        ->groupBy('payment_method')
        ->map(fn ($sales, string $method): array => ['label' => $method, 'total' => (float) $sales->sum('total')])
        ->sortByDesc('total')
        ->values();
    $paymentPalette = ['#496de5', '#18b989', '#39b9d6', '#f0b540', '#ed7080'];
    $paymentTotal = $paymentMethods->sum('total');
    $paymentSlices = [];
    $paymentOffset = 0;

    foreach ($paymentMethods as $index => $method) {
        $end = $paymentTotal > 0 ? $paymentOffset + ($method['total'] / $paymentTotal * 100) : 0;
        $paymentSlices[] = $method + [
            'color' => $paymentPalette[$index % count($paymentPalette)],
            'start' => $paymentOffset,
            'end' => $end,
        ];
        $paymentOffset = $end;
    }

    $paymentGradient = $paymentSlices
        ? 'conic-gradient('.implode(', ', array_map(fn (array $slice): string => "{$slice['color']} {$slice['start']}% {$slice['end']}%", $paymentSlices)).')'
        : '#e9eef5';

    return view('admin.dashboard', [
        'productsCount' => Product::count(),
        'lowStockCount' => Product::where('stock', '<=', 5)->count(),
        'lowStockProducts' => Product::query()->where('stock', '<=', 5)->orderBy('stock')->limit(5)->get(),
        'monthRevenue' => (float) $monthSales->sum('total'),
        'yearRevenue' => (float) Sale::query()->whereBetween('sold_at', [$now->copy()->startOfYear(), $now->copy()->endOfDay()])->sum('total'),
        'monthTransactionCount' => $monthSales->count(),
        'monthlySales' => $monthlySales,
        'maxMonthlyRevenue' => (float) $monthlySales->max('revenue'),
        'paymentSlices' => $paymentSlices,
        'paymentGradient' => $paymentGradient,
        'recentSales' => Sale::with('user')->orderByDesc('sold_at')->limit(6)->get(),
    ]);
};

Route::middleware(['auth', 'admin'])->group(function () use ($adminDashboard) {
    Route::resource('products', ProductController::class)->except(['index', 'show']);

    Route::get('/admin', $adminDashboard)->name('admin.dashboard');
    Route::get('/admin/kasir', [CashierController::class, 'index'])->name('admin.cashiers.index');
    Route::patch('/admin/kasir/{user}/status', [CashierController::class, 'toggleStatus'])->name('admin.cashiers.status');
    Route::delete('/admin/kasir/{user}', [CashierController::class, 'destroy'])->name('admin.cashiers.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
});

Route::get('/kasir', [KasirController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('kasir.index');
Route::get('/transaksi', [TransactionHistoryController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('transactions.index');
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () use ($adminDashboard) {
    if (Auth::user()->role === 'admin') {
        return $adminDashboard();
    }

    return view('kasir.dashboard', [
        'cashierProductCount' => Product::where('is_active', true)->count(),
        'cashierStockCount' => Product::where('is_active', true)->sum('stock'),
        'cashierSoldCount' => SaleItem::sum('quantity'),
        'cashierCategoryCount' => Product::query()
            ->where('is_active', true)
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->count('category'),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/struk', [StrukController::class, 'cetak'])
    ->middleware(['auth', 'verified', 'throttle:10,1'])
    ->name('struk.cetak');

require __DIR__.'/auth.php';
