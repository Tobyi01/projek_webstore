@extends('layouts.admin')

@section('title', 'Kelola Kasir - NesiaStore')
@section('page-heading', 'Kelola Kasir')

@section('content')
<section class="card border-0">
    <div class="card-body p-4">
        <div class="align-items-center d-flex flex-wrap justify-content-between mb-3 gap-2">
            <div>
                <h1 class="h5 mb-1">Akun Kasir</h1>
                <p class="mb-0 text-muted small">Lihat, nonaktifkan, atau hapus akun kasir yang terdaftar.</p>
            </div>
            <span class="badge text-bg-primary">{{ $cashiers->total() }} kasir</span>
        </div>

        @if (session('status'))
            <div class="alert alert-success" role="status">{{ session('status') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th scope="col">Nama</th>
                        <th scope="col">Email</th>
                        <th scope="col">Status</th>
                        <th scope="col">Terdaftar</th>
                        <th scope="col" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cashiers as $cashier)
                        <tr>
                            <td class="fw-semibold">{{ $cashier->name }}</td>
                            <td>{{ $cashier->email }}</td>
                            <td>
                                @if ($cashier->is_active)
                                    <span class="badge text-bg-success">Aktif</span>
                                @else
                                    <span class="badge text-bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td>{{ $cashier->created_at?->format('d M Y') ?? '—' }}</td>
                            <td>
                                <div class="d-flex flex-wrap justify-content-end gap-2">
                                    <form method="POST" action="{{ route('admin.cashiers.status', $cashier) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm {{ $cashier->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}">
                                            {{ $cashier->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.cashiers.destroy', $cashier) }}" onsubmit="return confirm('Hapus akun kasir {{ $cashier->name }} secara permanen? Riwayat transaksinya tetap disimpan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center text-muted">Belum ada akun kasir yang terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $cashiers->links() }}
    </div>
</section>
@endsection
