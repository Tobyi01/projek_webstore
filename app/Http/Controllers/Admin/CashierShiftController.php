<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CashierShift;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CashierShiftController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'status' => ['nullable', 'in:open,closed'],
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:from'],
        ]);

        $shiftsQuery = CashierShift::query()
            ->with('user')
            ->withSum(['sales as cash_sales_total' => fn ($query) => $query->where('payment_method', 'Tunai')], 'total')
            ->withCount('sales')
            ->when(
                ($filters['status'] ?? null) === 'open',
                fn ($query) => $query->whereNull('closed_at')
            )
            ->when(
                ($filters['status'] ?? null) === 'closed',
                fn ($query) => $query->whereNotNull('closed_at')
            )
            ->when(
                ! empty($filters['from']),
                fn ($query) => $query->whereDate('opened_at', '>=', $filters['from'])
            )
            ->when(
                ! empty($filters['to']),
                fn ($query) => $query->whereDate('opened_at', '<=', $filters['to'])
            );

        return view('admin.cashier-shifts.index', [
            'shifts' => $shiftsQuery->orderByDesc('opened_at')->paginate(20)->withQueryString(),
            'openShiftCount' => CashierShift::query()->whereNull('closed_at')->count(),
            'todayShiftCount' => CashierShift::query()->whereDate('opened_at', today())->count(),
            'todayCashSales' => (float) CashierShift::query()
                ->whereDate('opened_at', today())
                ->withSum(['sales as cash_sales_total' => fn ($query) => $query->where('payment_method', 'Tunai')], 'total')
                ->get()
                ->sum('cash_sales_total'),
            'filters' => $filters,
        ]);
    }
}
