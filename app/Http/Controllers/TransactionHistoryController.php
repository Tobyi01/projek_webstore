<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class TransactionHistoryController extends Controller
{
    public function index(Request $request): View
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
            ->with('items')
            ->whereBetween('sold_at', [$start, $end])
            ->orderByDesc('sold_at')
            ->get();

        $groups = $sales
            ->groupBy(fn (Sale $sale): string => $period === 'year'
                ? $sale->sold_at->format('Y-m')
                : $sale->sold_at->format('Y-m-d'))
            ->map(function ($groupSales, string $key) use ($period): array {
                $date = Carbon::parse($key)->locale('id');

                return [
                    'label' => $period === 'year'
                        ? $date->translatedFormat('F Y')
                        : $date->translatedFormat('l, d F Y'),
                    'sales' => $groupSales,
                    'total' => $groupSales->sum('total'),
                    'items' => $groupSales->sum(fn (Sale $sale): int => $sale->items->sum('quantity')),
                ];
            })
            ->values();

        return view('transaksi.index', [
            'period' => $period,
            'periodTitle' => ['day' => 'Harian', 'month' => 'Bulanan', 'year' => 'Tahunan'][$period],
            'start' => $start,
            'groups' => $groups,
            'saleCount' => $sales->count(),
            'itemCount' => $sales->sum(fn (Sale $sale): int => $sale->items->sum('quantity')),
            'totalRevenue' => $sales->sum('total'),
        ]);
    }
}