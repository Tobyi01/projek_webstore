@extends('layouts.admin')

@section('title', 'Aktivitas Login Kasir - NesiaStore')
@section('page-heading', 'Aktivitas Login Kasir')

@section('content')
<section class="card border-0">
    <div class="card-body p-4">
        <div class="align-items-center d-flex flex-wrap justify-content-between mb-3 gap-2">
            <div>
                <h1 class="h5 mb-1">Riwayat Aktivitas Autentikasi</h1>
                <p class="mb-0 text-muted small">Pantau login berhasil, login gagal, dan logout akun kasir.</p>
            </div>
            <span class="badge text-bg-primary">{{ $activities->total() }} aktivitas</span>
        </div>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th scope="col">Waktu</th>
                        <th scope="col">Kasir</th>
                        <th scope="col">Aktivitas</th>
                        <th scope="col">Alamat IP</th>
                        <th scope="col">Perangkat / Browser</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($activities as $activity)
                        <tr>
                            <td class="text-nowrap">{{ $activity->created_at?->format('d M Y, H:i:s') ?? '—' }}</td>
                            <td>
                                <div class="fw-semibold">{{ $activity->cashier_name }}</div>
                                <small class="text-muted">{{ $activity->cashier_email }}</small>
                            </td>
                            <td>
                                @if ($activity->activity === 'login')
                                    <span class="badge text-bg-success">Login berhasil</span>
                                @elseif ($activity->activity === 'failed')
                                    <span class="badge text-bg-danger">Login gagal</span>
                                @else
                                    <span class="badge text-bg-secondary">Logout</span>
                                @endif
                            </td>
                            <td>{{ $activity->ip_address ?? '—' }}</td>
                            <td class="small text-muted" style="max-width: 24rem; overflow-wrap: anywhere;">
                                {{ $activity->user_agent ?? '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center text-muted">Belum ada aktivitas login kasir.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $activities->links() }}
    </div>
</section>
@endsection
