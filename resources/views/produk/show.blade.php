@extends('layouts.admin')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between mb-4">
        <h1 class="h3">Detail Product</h1>

        <a href="{{ route('products.index') }}" class="btn btn-secondary">
            Kembali
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <table class="table">

                <tr>
                    <th>Image</th>
                    <td>
                        @if($product->image)
                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="rounded border" style="width: 180px; height: 140px; object-fit: cover;">
                        @else
                            <div class="d-flex align-items-center justify-content-center rounded border bg-light text-muted" style="width: 180px; height: 140px;">
                                <i class="bi bi-image fs-2" aria-hidden="true"></i>
                            </div>
                        @endif
                    </td>
                </tr>

                <tr>
                    <th width="200">Nama</th>
                    <td>{{ $product->name }}</td>
                </tr>

                <tr>
                    <th>Kategori</th>
                    <td>{{ $product->category ?? '-' }}</td>
                </tr>

                <tr>
                    <th>Deskripsi</th>
                    <td>{{ $product->description ?? '-' }}</td>
                </tr>

                <tr>
                    <th>Harga</th>
                    <td>
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </td>
                </tr>

                <tr>
                    <th>Stock</th>
                    <td>{{ $product->stock }}</td>
                </tr>

                <tr>
                    <th>Status</th>
                    <td>
                        @if($product->is_active)
                            <span class="badge bg-success">
                                Aktif
                            </span>
                        @else
                            <span class="badge bg-secondary">
                                Tidak Aktif
                            </span>
                        @endif
                    </td>
                </tr>

                <tr>
                    <th>Dibuat</th>
                    <td>
                        {{ $product->created_at->format('d-m-Y H:i') }}
                    </td>
                </tr>

            </table>

        </div>
    </div>

</div>

@endsection