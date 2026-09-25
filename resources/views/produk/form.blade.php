<div class="mb-3">
    <label class="form-label">Nama Product</label>
    <input
        type="text"
        name="name"
        class="form-control @error('name') is-invalid @enderror"
        value="{{ old('name', $product->name ?? '') }}"
    >

    @error('name')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Kategori</label>
    <input
        type="text"
        name="category"
        class="form-control"
        value="{{ old('category', $product->category ?? '') }}"
    >
</div>

<div class="mb-3">
    <label class="form-label">Deskripsi</label>
    <textarea
        name="description"
        class="form-control"
        rows="4"
    >{{ old('description', $product->description ?? '') }}</textarea>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Harga</label>
            <input
                type="number"
                name="price"
                class="form-control @error('price') is-invalid @enderror"
                value="{{ old('price', $product->price ?? '') }}"
            >

            @error('price')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Stock</label>
            <input
                type="number"
                name="stock"
                class="form-control"
                value="{{ old('stock', $product->stock ?? 0) }}"
            >
        </div>
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Image</label>
    <input
        id="product-image"
        type="file"
        name="image"
        class="form-control @error('image') is-invalid @enderror"
        accept="image/jpeg,image/png,image/webp,image/gif"
    >
    <div class="form-text">Format JPG, PNG, WEBP, atau GIF. Maksimal 2 MB.</div>

    @error('image')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror

    <div class="mt-2">
        <img
            id="product-image-preview"
            src="{{ !empty($product?->image) ? Storage::url($product->image) : '' }}"
            alt="Preview gambar produk"
            class="rounded border {{ empty($product?->image) ? 'd-none' : '' }}"
            style="width: 160px; height: 120px; object-fit: cover;"
        >
        <span id="product-image-empty" class="text-muted {{ !empty($product?->image) ? 'd-none' : '' }}">Belum ada gambar.</span>
    </div>
</div>

<div class="form-check mb-3">
    <input
        type="checkbox"
        name="is_active"
        value="1"
        class="form-check-input"
        id="is_active"
        {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}
    >

    <label class="form-check-label" for="is_active">
        Product Aktif
    </label>
</div>

<script>
    document.getElementById('product-image').addEventListener('change', function (event) {
        const file = event.target.files[0];
        const preview = document.getElementById('product-image-preview');
        const emptyState = document.getElementById('product-image-empty');

        if (!file) {
            preview.classList.toggle('d-none', !preview.getAttribute('src'));
            emptyState.classList.toggle('d-none', Boolean(preview.getAttribute('src')));
            return;
        }

        preview.src = URL.createObjectURL(file);
        preview.classList.remove('d-none');
        emptyState.classList.add('d-none');
    });
</script>