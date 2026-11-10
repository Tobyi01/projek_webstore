@extends('layouts.admin')

@section('title', 'Shift Kasir - NesiaStore')
@section('page-heading', 'Shift Kasir')

@section('content')
<section class="card border-0">
    <div class="card-body p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
            <div>
                <h1 class="h5 mb-1">Riwayat Shift Kasir</h1>
                <p class="mb-0 text-muted small">Pantau shift aktif, modal, transaksi, dan rekonsiliasi kas kasir.</p>
            </div>
            <span class="badge text-bg-primary">{{ $shifts->total() }} shift</span>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-sm-4">
                <div class="rounded-3 border bg-light p-3 h-100">
                    <div class="text-muted small">Shift sedang aktif</div>
                    <strong class="fs-5">{{ number_format($openShiftCount, 0, ',', '.') }}</strong>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="rounded-3 border bg-light p-3 h-100">
                    <div class="text-muted small">Shift dibuka hari ini</div>
                    <strong class="fs-5">{{ number_format($todayShiftCount, 0, ',', '.') }}</strong>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="rounded-3 border bg-light p-3 h-100">
                    <div class="text-muted small">Penjualan tunai dari shift hari ini</div>
                    <strong class="fs-5">Rp {{ number_format($todayCashSales, 0, ',', '.') }}</strong>
                </div>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.cashier-shifts.index') }}" class="row g-2 align-items-end mb-4">
            <div class="col-sm-4 col-lg-3">
                <label for="status" class="form-label small">Status shift</label>
                <select id="status" name="status" class="form-select">
                    <option value="">Semua status</option>
                    <option value="open" @selected(($filters['status'] ?? '') === 'open')>Sedang berjalan</option>
                    <option value="closed" @selected(($filters['status'] ?? '') === 'closed')>Sudah ditutup</option>
                </select>
            </div>
            <div class="col-sm-3 col-lg-2">
                <label for="from" class="form-label small">Dibuka dari</label>
                <input id="from" type="date" name="from" value="{{ $filters['from'] ?? '' }}" class="form-control">
            </div>
            <div class="col-sm-3 col-lg-2">
                <label for="to" class="form-label small">Sampai</label>
                <input id="to" type="date" name="to" value="{{ $filters['to'] ?? '' }}" class="form-control">
            </div>
            <div class="col-sm-2">
                <button class="btn btn-primary" type="submit">Filter</button>
                <a class="btn btn-outline-secondary" href="{{ route('admin.cashier-shifts.index') }}">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Kasir</th>
                        <th>Status</th>
                        <th>Dibuka</th>
                        <th>Ditutup</th>
                        <th>Modal awal</th>
                        <th>Penjualan tunai</th>
                        <th>Kas akhir sistem</th>
                        <th>Selisih tercatat</th>
                        <th>Transaksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($shifts as $shift)
                        @php
                            $cashSales = (float) ($shift->cash_sales_total ?? 0);
                            $expectedCash = $shift->expected_cash ?? ((float) $shift->opening_cash + $cashSales);
                        @endphp
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $shift->user?->name ?? 'Akun dihapus' }}</div>
                                <small class="text-muted">{{ $shift->user?->email ?? '—' }}</small>
                            </td>
                            <td>
                                @if ($shift->closed_at)
                                    <span class="badge text-bg-secondary">Ditutup</span>
                                @else
                                    <span class="badge text-bg-success">Berjalan</span>
                                @endif
                            </td>
                            <td class="text-nowrap">{{ $shift->opened_at->format('d/m/Y H:i') }}</td>
                            <td class="text-nowrap">{{ $shift->closed_at?->format('d/m/Y H:i') ?? '—' }}</td>
                            <td class="text-nowrap">Rp {{ number_format((float) $shift->opening_cash, 0, ',', '.') }}</td>
                            <td class="text-nowrap">Rp {{ number_format($cashSales, 0, ',', '.') }}</td>
                            <td class="text-nowrap">
                                @if ($shift->closed_at)
                                    Rp {{ number_format((float) $expectedCash, 0, ',', '.') }}
                                @else
                                    <span class="text-muted">Berjalan</span>
                                @endif
                            </td>
                            <td class="text-nowrap">
                                @if ($shift->closed_at)
                                    Rp {{ number_format((float) $shift->cash_difference, 0, ',', '.') }}
                                @else
                                    —
                                @endif
                            </td>
                            <td>{{ number_format($shift->sales_count, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-4 text-center text-muted">Belum ada shift pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $shifts->links() }}
    </div>
</section>
@endsection
