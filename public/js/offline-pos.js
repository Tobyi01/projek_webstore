(() => {
    const $ = id => document.getElementById(id);
    const money = amount => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(amount || 0);
    let snapshot;
    let cart = [];
    let busy = false;

    function updateConnection() {
        $('connectionStatus').textContent = navigator.onLine ? 'Online · sinkronisasi aktif' : 'Offline';
        $('connectionStatus').style.color = navigator.onLine ? '#167447' : '#a3660d';
    }

    async function updateQueue() {
        const queue = await window.NesiaOffline.getQueue();
        $('queueStatus').textContent = queue.length ? `${queue.length} transaksi menunggu sinkronisasi.` : 'Tidak ada transaksi yang menunggu sinkronisasi.';
    }

    function renderProducts() {
        const query = $('search').value.trim().toLowerCase();
        const matches = (snapshot?.products || []).filter(product => product.name.toLowerCase().includes(query) || product.code.toLowerCase().includes(query) || product.barcode.toLowerCase().includes(query));
        $('products').innerHTML = matches.map(product => {
            const status = product.stock <= 0 ? 'Stok habis' : `Stok ${product.stock}`;
            return `<div class="product ${product.stock > 0 && product.stock <= 5 ? 'low' : ''}"><div><strong>${product.name}</strong><small>${status} · ${money(product.price)}</small></div><button type="button" data-add="${product.id}" ${product.stock <= 0 ? 'disabled' : ''}>Tambah</button></div>`;
        }).join('') || '<p>Produk tidak ditemukan pada katalog tersimpan.</p>';
    }

    function renderCart() {
        $('cart').innerHTML = cart.map((item, index) => `<div class="cart-item"><span>${item.product_name} × ${item.qty}<small> · ${money(item.unit_price * item.qty)}</small></span><button type="button" data-remove="${index}" aria-label="Hapus ${item.product_name}">Hapus</button></div>`).join('') || '<p>Keranjang kosong.</p>';
        const total = cart.reduce((sum, item) => sum + item.unit_price * item.qty, 0);
        $('total').textContent = `Total ${money(total)}`;
        if ($('method').value !== 'Tunai') $('paid').value = total || '';
        if ($('method').value === 'Tunai' && !$('paid').dataset.edited) $('paid').value = total || '';
    }

    async function saveSale() {
        if (busy || !cart.length) return;
        const total = cart.reduce((sum, item) => sum + item.unit_price * item.qty, 0);
        const method = $('method').value;
        const paid = Number($('paid').value) || 0;
        if (method === 'Tunai' && paid < total) {
            alert('Pembayaran tunai kurang.');
            return;
        }
        if (method !== 'Tunai' && !confirm(`Pastikan pembayaran ${method} sebesar ${money(total)} telah diterima. Lanjutkan?`)) return;

        busy = true;
        $('saveButton').disabled = true;
        try {
            const now = new Date();
            const transactionNumber = `OFF-${now.toISOString().replace(/\D/g, '').slice(0, 14)}-${crypto.randomUUID().replace(/-/g, '').slice(0, 8).toUpperCase()}`;
            const sale = {
                transaction_number: transactionNumber,
                customer: $('customer').value,
                payment_method: method,
                paid,
                sold_at: now.toISOString(),
                items: cart.map(item => ({ ...item })),
            };
            await window.NesiaOffline.queueTransaction(sale);
            $('receipt').innerHTML = `<h2>NesiaStore · Salinan struk</h2><p>No. transaksi: ${sale.transaction_number}</p><p>Waktu: ${new Date(sale.sold_at).toLocaleString('id-ID')}</p><p>Pelanggan: ${sale.customer}</p>${sale.items.map(item => `<div class="cart-item"><span>${item.product_name} × ${item.qty}</span><strong>${money(item.unit_price * item.qty)}</strong></div>`).join('')}<p class="total">Total ${money(total)}</p><p>${sale.payment_method}: ${money(sale.paid)}</p><p>Periksa antrean sinkronisasi sebelum menutup sesi.</p>`;
            window.print();
            cart = [];
            $('paid').dataset.edited = '';
            renderCart();
            await updateQueue();
            alert(`Transaksi ${transactionNumber} tersimpan di perangkat.${navigator.onLine ? ' Sedang disinkronkan.' : ' Akan disinkronkan saat internet tersambung.'}`);
            if (navigator.onLine) {
                const result = await window.NesiaOffline.syncQueue();
                await updateQueue();
                if (result.pending === 0) alert('Transaksi berhasil disinkronkan.');
            }
        } catch {
            alert('Tidak dapat menyimpan transaksi di perangkat ini. Pastikan browser mengizinkan penyimpanan lokal.');
        } finally {
            busy = false;
            $('saveButton').disabled = false;
        }
    }

    async function initialize() {
        updateConnection();
        snapshot = await window.NesiaOffline.getSnapshot();
        if (!snapshot?.products?.length || !snapshot.userId) {
            $('cashierName').textContent = 'Sesi offline belum disiapkan';
            $('message').textContent = 'Sebelum offline, login dan buka halaman Kasir saat online agar katalog tersimpan di perangkat ini.';
            $('paymentPanel').hidden = true;
            $('catalogPanel').hidden = true;
            $('syncButton').disabled = true;
            return;
        }
        $('cashierName').textContent = `Kasir: ${snapshot.userName} · katalog terakhir diperbarui ${new Date(snapshot.savedAt).toLocaleString('id-ID')}`;
        renderProducts();
        renderCart();
        await updateQueue();
        if (navigator.onLine) {
            await window.NesiaOffline.syncQueue();
            await updateQueue();
        }
    }

    $('search').addEventListener('input', renderProducts);
    $('products').addEventListener('click', event => {
        const button = event.target.closest('[data-add]');
        if (!button) return;
        const product = snapshot.products.find(item => item.id === Number(button.dataset.add));
        const item = cart.find(row => row.product_id === product.id);
        if (item) {
            if (item.qty >= product.stock) return alert('Jumlah melebihi stok terakhir yang diketahui.');
            item.qty++;
        } else {
            cart.push({ product_id: product.id, product_name: product.name, unit_price: product.price, qty: 1, stock: product.stock, offline_token: product.offline_token });
        }
        renderCart();
    });
    $('cart').addEventListener('click', event => {
        const button = event.target.closest('[data-remove]');
        if (button) cart.splice(Number(button.dataset.remove), 1);
        renderCart();
    });
    $('method').addEventListener('change', renderCart);
    $('paid').addEventListener('input', () => $('paid').dataset.edited = 'true');
    $('saveButton').addEventListener('click', saveSale);
    $('syncButton').addEventListener('click', async () => {
        const result = await window.NesiaOffline.syncQueue();
        await updateQueue();
        alert(result.synced ? `${result.synced} transaksi berhasil disinkronkan.` : 'Belum ada transaksi yang berhasil disinkronkan. Periksa koneksi dan sesi login.');
    });
    window.addEventListener('online', updateConnection);
    window.addEventListener('offline', updateConnection);
    window.addEventListener('offline-queue-updated', updateQueue);
    initialize().catch(() => {
        $('message').textContent = 'Data offline tidak dapat dibuka. Sambungkan ke internet lalu buka halaman kasir kembali.';
    });
})();
