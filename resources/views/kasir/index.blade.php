@extends('layouts.admin')

@section('title', 'Kasir - NesiaStore')

@section('content')
<style>
    .pos-page { --pos-blue: #1769ed; --pos-ink: #102858; --pos-muted: #74819c; background: #f4f7fb; margin: -1.5rem; min-height: calc(100vh - 3rem); padding: 1.25rem; }
    .pos-header { display: flex; justify-content: space-between; gap: 1rem; align-items: end; margin-bottom: 1rem; }
    .pos-header h1 { color: var(--pos-ink); font-size: 1.35rem; font-weight: 800; margin: 0; }
    .pos-header p { color: var(--pos-muted); font-size: .78rem; margin: .25rem 0 0; }
    .transaction-meta { color: var(--pos-muted); font-size: .72rem; text-align: right; }
    .transaction-meta strong { color: var(--pos-ink); display: block; font-size: .9rem; }
    .pos-grid { display: grid; grid-template-columns: minmax(0, 1fr) 22rem; gap: 1rem; align-items: start; }
    .pos-card { background: #fff; border: 1px solid #e3e9f2; border-radius: .55rem; box-shadow: 0 .35rem 1.2rem rgba(27, 65, 123, .06); overflow: visible; }
    .pos-card + .pos-card { margin-top: 1rem; }
    .pos-card-title { border-bottom: 1px solid #edf1f6; color: var(--pos-ink); font-size: .82rem; font-weight: 800; padding: .85rem 1rem; }
    .pos-card-body { padding: 1rem; }
    .search-row { display: flex; gap: .6rem; }
    .search-wrap { flex: 1; position: relative; }
    .search-wrap input, .customer-grid input, .customer-grid select, .summary-input, .payment-input { border: 1px solid #d6dfeb; border-radius: .35rem; color: var(--pos-ink); min-height: 2.5rem; padding: .55rem .7rem; width: 100%; }
    .search-wrap input:focus, .customer-grid input:focus, .customer-grid select:focus, .summary-input:focus, .payment-input:focus { border-color: var(--pos-blue); box-shadow: 0 0 0 3px rgba(23, 105, 237, .12); outline: 0; }
    .product-results { background: #fff; border: 1px solid #d6dfeb; border-radius: .35rem; display: none; left: 0; max-height: 17rem; overflow-y: auto; position: absolute; right: 0; top: calc(100% + .3rem); z-index: 10; }
    .product-item { align-items: center; border-bottom: 1px solid #edf1f6; cursor: pointer; display: flex; justify-content: space-between; gap: 1rem; padding: .7rem .8rem; }
    .product-item:last-child { border-bottom: 0; }
    .product-item:hover { background: #f0f6ff; }
    .product-image { background: #f4f7fb; border-radius: .3rem; flex: 0 0 2.8rem; height: 2.8rem; object-fit: cover; width: 2.8rem; }
    .product-image-placeholder { align-items: center; background: #f4f7fb; border-radius: .3rem; color: var(--pos-muted); display: flex; flex: 0 0 2.8rem; height: 2.8rem; justify-content: center; width: 2.8rem; }
    .product-info { align-items: center; display: flex; gap: .65rem; min-width: 0; }
    .product-name { color: var(--pos-ink); font-size: .78rem; font-weight: 700; }
    .product-code, .product-stock { color: var(--pos-muted); font-size: .68rem; }
    .product-price { color: var(--pos-blue); font-size: .76rem; font-weight: 700; white-space: nowrap; }
    .btn-pos { background: var(--pos-blue); border: 0; border-radius: .35rem; color: #fff; font-size: .75rem; font-weight: 700; padding: .65rem 1rem; }
    .customer-grid { display: grid; grid-template-columns: 1fr 1fr; gap: .7rem; margin-top: .8rem; }
    .field-label, .payment-label { color: #52617d; display: block; font-size: .68rem; font-weight: 700; margin-bottom: .35rem; }
    .table-wrap { overflow-x: auto; }
    .cart-table { border-collapse: collapse; min-width: 600px; width: 100%; }
    .cart-table th { background: #f8faff; color: #52617d; font-size: .65rem; padding: .7rem; text-align: left; white-space: nowrap; }
    .cart-table td { border-top: 1px solid #edf1f6; color: var(--pos-ink); font-size: .74rem; padding: .7rem; vertical-align: middle; }
    .cart-table .text-right { text-align: right; }
    .empty-cart { color: var(--pos-muted); padding: 3.5rem 1rem !important; text-align: center; }
    .qty-control { align-items: center; display: flex; gap: .3rem; }
    .qty-control button { background: #eef4ff; border: 0; border-radius: .25rem; color: var(--pos-blue); font-weight: 800; height: 1.7rem; width: 1.7rem; }
    .qty-control input { border: 1px solid #d6dfeb; border-radius: .25rem; text-align: center; width: 3rem; }
    .remove-btn { background: transparent; border: 0; color: #d9364f; font-size: 1.1rem; }
    .summary-row { align-items: center; color: #52617d; display: flex; font-size: .72rem; justify-content: space-between; margin-bottom: .75rem; }
    .summary-row strong { color: var(--pos-ink); }
    .summary-input { max-width: 6.5rem; min-height: 2rem; padding: .35rem .5rem; text-align: right; }
    .total-box { background: #f1f5fb; border-radius: .35rem; margin: 1rem 0; padding: .85rem; }
    .total-label { color: var(--pos-muted); font-size: .64rem; font-weight: 700; }
    .total-value { color: var(--pos-ink); font-size: 1.45rem; font-weight: 800; margin-top: .15rem; }
    .payment-box { background: #edf6ff; border: 1px solid #cfe2fb; border-radius: .35rem; padding: .8rem; }
    .payment-input { font-size: 1.1rem; font-weight: 800; min-height: 2.75rem; text-align: right; }
    .payment-method { display: grid; gap: .35rem; grid-template-columns: repeat(3, 1fr); }
    .payment-method button { background: #fff; border: 1px solid #d6dfeb; border-radius: .25rem; color: #52617d; font-size: .65rem; padding: .45rem .2rem; }
    .payment-method button.active { background: var(--pos-blue); border-color: var(--pos-blue); color: #fff; }
    .change-box { background: #d9f3e5; border-radius: .3rem; margin-top: .8rem; padding: .65rem; }
    .change-box.short-payment { background: #fff0d6; }
    .change-label { color: #4b8163; font-size: .62rem; font-weight: 700; }
    .short-payment .change-label { color: #9a671d; }
    .change-value { color: #167447; font-size: 1.15rem; font-weight: 800; }
    .short-payment .change-value { color: #a3660d; }
    .quick-payments { display: flex; flex-wrap: wrap; gap: .35rem; margin-top: .55rem; }
    .quick-payments button { background: #fff; border: 1px solid #bfd5ef; border-radius: .25rem; color: var(--pos-blue); font-size: .65rem; padding: .35rem .5rem; }
    .action-area { display: grid; gap: .5rem; grid-template-columns: 1fr 1fr; margin-top: 1rem; }
    .action-area button { border: 0; border-radius: .3rem; color: #fff; font-size: .72rem; font-weight: 800; padding: .7rem .5rem; }
    .btn-hold { background: #f2b705; } .btn-cancel { background: #dc3545; } .btn-pay { background: #16834e; grid-column: 1 / -1; }
    .receipt-status { background: #eaf7ef; border-radius: .3rem; color: #226b44; display: none; font-size: .67rem; line-height: 1.5; margin-top: .65rem; padding: .6rem; }
    .receipt-status.is-visible { display: block; }.receipt-status.is-error { background: #fff3df; color: #865814; }
    .print-receipt { display: none; }
    .print-receipt h1, .print-receipt p { margin: 0; }
    .receipt-head { border-bottom: 1px dashed #222; margin-bottom: .65rem; padding-bottom: .65rem; text-align: center; }
    .receipt-head h1 { font-size: 18px; font-weight: 700; }
    .receipt-head p { font-size: 11px; margin-top: 3px; }
    .receipt-meta { font-size: 11px; margin-bottom: .65rem; }
    .receipt-meta div, .receipt-totals div { display: flex; justify-content: space-between; gap: .6rem; }
    .receipt-items { border-bottom: 1px dashed #222; border-top: 1px dashed #222; margin: .55rem 0; padding: .45rem 0; }
    .receipt-item { margin-bottom: .5rem; }.receipt-item:last-child { margin-bottom: 0; }
    .receipt-item-name { font-weight: 700; overflow-wrap: anywhere; }
    .receipt-item-detail { display: flex; justify-content: space-between; gap: .5rem; }
    .receipt-totals { font-size: 11px; }.receipt-totals div { margin: .2rem 0; }
    .receipt-totals .receipt-grand { border-top: 1px solid #222; font-size: 14px; font-weight: 700; margin-top: .45rem; padding-top: .45rem; }
    .receipt-thanks { border-top: 1px dashed #222; font-size: 11px; margin-top: .7rem !important; padding-top: .6rem; text-align: center; }
    @media (max-width: 900px) { .pos-grid { grid-template-columns: 1fr; } .pos-header { align-items: start; } }
    @media (max-width: 520px) { .pos-page { margin: -1rem; padding: .75rem; } .customer-grid { grid-template-columns: 1fr; } .search-row { flex-direction: column; } .pos-header { flex-direction: column; } .transaction-meta { text-align: left; } }
    @media print {
        @page { size: 80mm auto; margin: 4mm; }
        body { background: #fff !important; }
        body * { visibility: hidden !important; }
        #printReceipt, #printReceipt * { visibility: visible !important; }
        #printReceipt { display: block !important; left: 0; margin: 0 auto; max-width: 72mm; position: absolute; top: 0; width: 100%; color: #111; font: 12px/1.4 Arial, sans-serif; }
    }
</style>

<div class="pos-page">
    <header class="pos-header"><div><h1>NesiaStore Kasir</h1><p>Jl. Contoh No. 123 &bull; Jember &bull; Telp. 0812-xxxx-xxxx</p></div><div class="transaction-meta"><span>No. Transaksi</span><strong id="transactionNumber"></strong><span id="currentDate"></span></div></header>
    <main class="pos-grid">
        <section>
            <div class="pos-card"><div class="pos-card-title">Tambah Barang</div><div class="pos-card-body"><div class="search-row"><div class="search-wrap"><input id="searchProduct" type="search" placeholder="Cari nama barang / kode / barcode..." autocomplete="off" autofocus><div class="product-results" id="productResults"></div></div><button class="btn-pos" id="searchButton" type="button">Cari</button></div><div class="customer-grid"><div><label class="field-label" for="customerType">Pelanggan</label><select id="customerType"><option>Umum</option><option>Pelanggan Member</option><option>Member VIP</option></select></div><div><label class="field-label" for="customerContact">No. Member / HP</label><input id="customerContact" type="text" placeholder="Opsional"></div></div></div></div>
            <div class="pos-card"><div class="pos-card-title d-flex justify-content-between"><span>Keranjang Belanja</span><span id="itemCount">0 Item</span></div><div class="table-wrap"><table class="cart-table"><thead><tr><th>No</th><th>Barang</th><th>Harga</th><th>Qty</th><th class="text-right">Subtotal</th><th></th></tr></thead><tbody id="cartBody"></tbody></table></div></div>
        </section>
        <aside class="pos-card"><div class="pos-card-title">Ringkasan Pembayaran</div><div class="pos-card-body"><div class="summary-row"><span>Total Item</span><strong id="totalQty">0</strong></div><div class="summary-row"><span>Subtotal</span><strong id="subtotal">Rp 0</strong></div><div class="summary-row"><span>Diskon (%)</span><input class="summary-input" id="discountPercent" type="number" value="0" min="0" max="100"></div><div class="summary-row"><span>Diskon (Rp)</span><input class="summary-input" id="discountAmount" type="number" value="0" min="0"></div><div class="summary-row"><span>Pajak / PPN</span><input class="summary-input" id="tax" type="number" value="0" min="0"></div><div class="summary-row"><span>Biaya Lain</span><input class="summary-input" id="otherFee" type="number" value="0" min="0"></div><div class="total-box"><div class="total-label">TOTAL AKHIR</div><div class="total-value" id="grandTotal">Rp 0</div></div><div class="payment-box"><label class="payment-label" for="payment">Uang Dibayar</label><input class="payment-input" id="payment" type="number" min="0" placeholder="0"><div class="payment-label mt-3">Nominal Cepat</div><div class="quick-payments" id="quickPayments"></div><div class="payment-label mt-3">Metode Pembayaran</div><div class="payment-method" id="paymentMethod"><button type="button" class="active">Tunai</button><button type="button">QRIS</button><button type="button">Debit</button><button type="button">Kredit</button><button type="button">E-Wallet</button><button type="button">Transfer</button></div><div class="change-box" id="changeBox"><div class="change-label" id="changeLabel">KEMBALIAN</div><div class="change-value" id="change">Rp 0</div></div></div><div class="action-area"><button type="button" class="btn-hold" id="holdButton">Tahan</button><button type="button" class="btn-cancel" id="cancelButton">Batal</button><button type="button" class="btn-pay" id="payButton">BAYAR &amp; CETAK</button></div><div id="receiptStatus" class="receipt-status" role="status" aria-live="polite"></div></div></aside>
    </main>
</div>
<div id="printReceipt" class="print-receipt" aria-live="polite"></div>

<script id="product-data" type="application/json">@json($productData)</script>
<script>
const products = JSON.parse(document.getElementById('product-data').textContent);
const receiptPrintUrl = @json(route('struk.cetak'));
let cart = [], selectedPayment = 'Tunai';
const rupiah = value => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(value || 0);
const $ = id => document.getElementById(id);
const grandTotal = () => { const subtotal = cart.reduce((sum, item) => sum + item.price * item.qty, 0); const percent = subtotal * (Number($('discountPercent').value) || 0) / 100; return Math.max(0, subtotal - percent - (Number($('discountAmount').value) || 0) + (Number($('tax').value) || 0) + (Number($('otherFee').value) || 0)); };
function productImage(product) { return product.image ? `<img class="product-image" src="${product.image}" alt="${product.name}">` : '<span class="product-image-placeholder" aria-hidden="true">&#9633;</span>'; }
function renderResults() { const keyword = $('searchProduct').value.toLowerCase().trim(), box = $('productResults'); if (!keyword) { box.style.display = 'none'; return; } const results = products.filter(product => [product.name, product.code, product.barcode].some(value => value.toLowerCase().includes(keyword))); box.innerHTML = results.length ? results.map(product => `<div class="product-item" data-product-id="${product.id}"><div class="product-info">${productImage(product)}<div><div class="product-name">${product.name}</div><div class="product-code">${product.code} - ${product.barcode} &bull; Stok ${product.stock}</div></div></div><div class="product-price">${rupiah(product.price)}</div></div>`).join('') : '<div class="product-item">Barang tidak ditemukan</div>'; box.style.display = 'block'; }
function addToCart(id) { const product = products.find(item => item.id === id); if (!product) return; const item = cart.find(row => row.id === id); if (item) { if (item.qty < product.stock) item.qty++; else alert('Jumlah melebihi stok yang tersedia.'); } else cart.push({ ...product, qty: 1 }); $('searchProduct').value = ''; $('productResults').style.display = 'none'; renderCart(); }
function renderCart() { const body = $('cartBody'); body.innerHTML = cart.length ? cart.map((item, index) => `<tr><td>${index + 1}</td><td><div class="product-info">${productImage(item)}<div><strong>${item.name}</strong><div class="product-code">${item.code}</div></div></div></td><td>${rupiah(item.price)}</td><td><div class="qty-control"><button type="button" data-action="minus" data-id="${item.id}">-</button><input type="number" min="1" max="${item.stock}" value="${item.qty}" data-action="qty" data-id="${item.id}"><button type="button" data-action="plus" data-id="${item.id}">+</button></div></td><td class="text-right"><strong>${rupiah(item.price * item.qty)}</strong></td><td><button type="button" class="remove-btn" data-action="remove" data-id="${item.id}" aria-label="Hapus ${item.name}">&times;</button></td></tr>`).join('') : '<tr><td class="empty-cart" colspan="6">Keranjang masih kosong.<br>Silakan cari atau scan barang.</td></tr>'; calculateTotal(); }
function calculateTotal() { const qty = cart.reduce((sum, item) => sum + item.qty, 0); $('totalQty').textContent = qty; $('itemCount').textContent = `${qty} Item`; $('subtotal').textContent = rupiah(cart.reduce((sum, item) => sum + item.price * item.qty, 0)); $('grandTotal').textContent = rupiah(grandTotal()); calculateChange(); renderQuickPayments(); }
function calculateChange() { const difference = (Number($('payment').value) || 0) - grandTotal(); $('changeBox').classList.toggle('short-payment', difference < 0); $('changeLabel').textContent = difference < 0 ? 'UANG KURANG' : 'KEMBALIAN'; $('change').textContent = rupiah(Math.abs(difference)); }
function renderQuickPayments() { const total = grandTotal(), values = total ? [...new Set([Math.ceil(total / 10000) * 10000, Math.ceil(total / 50000) * 50000, Math.ceil(total / 100000) * 100000])] : []; $('quickPayments').innerHTML = values.map(value => `<button type="button" data-payment="${value}">${rupiah(value)}</button>`).join('') + (total ? '<button type="button" data-payment="exact">Uang Pas</button>' : ''); }
function escapeReceiptText(value) { return String(value).replace(/[&<>"']/g, character => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[character]); }
function renderReceipt() {
    const subtotal = cart.reduce((sum, item) => sum + item.price * item.qty, 0);
    const percentDiscount = subtotal * (Number($('discountPercent').value) || 0) / 100;
    const amountDiscount = Number($('discountAmount').value) || 0;
    const tax = Number($('tax').value) || 0;
    const otherFee = Number($('otherFee').value) || 0;
    const paid = Number($('payment').value) || 0;
    const total = grandTotal();
    const receiptRows = cart.map(item => `<div class="receipt-item"><div class="receipt-item-name">${escapeReceiptText(item.name)}</div><div class="receipt-item-detail"><span>${item.qty} x ${rupiah(item.price)}</span><strong>${rupiah(item.price * item.qty)}</strong></div></div>`).join('');
    const now = new Date();
    $('printReceipt').innerHTML = `<header class="receipt-head"><h1>NesiaStore</h1><p>Jl. Contoh No. 123 - Jember</p><p>Telp. 0812-xxxx-xxxx</p></header><div class="receipt-meta"><div><span>No. Transaksi</span><strong>${escapeReceiptText($('transactionNumber').textContent)}</strong></div><div><span>Waktu</span><span>${escapeReceiptText(now.toLocaleString('id-ID'))}</span></div><div><span>Pelanggan</span><span>${escapeReceiptText($('customerType').value)}</span></div></div><div class="receipt-items">${receiptRows}</div><div class="receipt-totals"><div><span>Subtotal</span><span>${rupiah(subtotal)}</span></div>${percentDiscount ? `<div><span>Diskon (${Number($('discountPercent').value)}%)</span><span>-${rupiah(percentDiscount)}</span></div>` : ''}${amountDiscount ? `<div><span>Diskon tambahan</span><span>-${rupiah(amountDiscount)}</span></div>` : ''}${tax ? `<div><span>Pajak / PPN</span><span>${rupiah(tax)}</span></div>` : ''}${otherFee ? `<div><span>Biaya lain</span><span>${rupiah(otherFee)}</span></div>` : ''}<div class="receipt-grand"><span>TOTAL</span><span>${rupiah(total)}</span></div><div><span>${escapeReceiptText(selectedPayment)}</span><span>${rupiah(paid)}</span></div><div><span>Kembalian</span><span>${rupiah(paid - total)}</span></div></div><p class="receipt-thanks">Terima kasih atas kunjungan Anda.</p>`;
}
function receiptPayload() { return { transaction_number: $('transactionNumber').textContent, customer: $('customerType').value, payment_method: selectedPayment, discount_percent: Number($('discountPercent').value) || 0, discount_amount: Number($('discountAmount').value) || 0, tax: Number($('tax').value) || 0, other_fee: Number($('otherFee').value) || 0, paid: Number($('payment').value) || 0, items: cart.map(({ id, qty }) => ({ product_id: id, qty })) }; }
function setTransactionMeta() { const now = new Date(), suffix = Math.random().toString(36).slice(2, 6).toUpperCase(); $('transactionNumber').textContent = `TRX-${now.toISOString().slice(0, 10).replaceAll('-', '')}-${String(now.getHours() * 3600 + now.getMinutes() * 60 + now.getSeconds()).padStart(6, '0')}-${suffix}`; $('currentDate').textContent = now.toLocaleString('id-ID'); }
function resetTransaction() { cart = []; $('payment').value = ''; $('printReceipt').innerHTML = ''; ['discountPercent', 'discountAmount', 'tax', 'otherFee'].forEach(id => $(id).value = 0); renderCart(); setTransactionMeta(); }
$('searchProduct').addEventListener('input', renderResults); $('searchButton').addEventListener('click', renderResults); $('searchProduct').addEventListener('keydown', event => { if (event.key === 'Enter') { const first = $('productResults').querySelector('[data-product-id]'); if (first) addToCart(Number(first.dataset.productId)); } }); $('productResults').addEventListener('click', event => { const item = event.target.closest('[data-product-id]'); if (item) addToCart(Number(item.dataset.productId)); });
$('cartBody').addEventListener('click', event => { const control = event.target.closest('[data-action]'); if (!control) return; const item = cart.find(row => row.id === Number(control.dataset.id)); if (!item) return; if (control.dataset.action === 'remove') cart = cart.filter(row => row.id !== item.id); if (control.dataset.action === 'minus') item.qty--; if (control.dataset.action === 'plus' && item.qty < item.stock) item.qty++; if (item.qty < 1) cart = cart.filter(row => row.id !== item.id); renderCart(); }); $('cartBody').addEventListener('change', event => { if (event.target.dataset.action !== 'qty') return; const item = cart.find(row => row.id === Number(event.target.dataset.id)); if (item) item.qty = Math.min(item.stock, Math.max(1, Number(event.target.value) || 1)); renderCart(); });
['discountPercent', 'discountAmount', 'tax', 'otherFee'].forEach(id => $(id).addEventListener('input', calculateTotal)); $('payment').addEventListener('input', calculateChange); $('quickPayments').addEventListener('click', event => { const button = event.target.closest('[data-payment]'); if (!button) return; $('payment').value = button.dataset.payment === 'exact' ? grandTotal() : button.dataset.payment; calculateChange(); }); $('paymentMethod').addEventListener('click', event => { const button = event.target.closest('button'); if (!button) return; selectedPayment = button.textContent; document.querySelectorAll('#paymentMethod button').forEach(item => item.classList.toggle('active', item === button)); });
$('holdButton').addEventListener('click', () => alert(cart.length ? `Transaksi ${$('transactionNumber').textContent} ditahan.` : 'Tidak ada transaksi untuk ditahan.'));
$('cancelButton').addEventListener('click', () => { if (cart.length && confirm('Batalkan transaksi ini?')) resetTransaction(); });
$('payButton').addEventListener('click', () => {
    if (!cart.length) return alert('Keranjang masih kosong.');
    if ((Number($('payment').value) || 0) < grandTotal()) return alert('Uang pembayaran masih kurang.');
    const button = $('payButton');
    if (button.disabled) return;
    const status = $('receiptStatus');
    const payload = receiptPayload();
    button.disabled = true;
    button.textContent = 'MENGIRIM KE PRINTER...';
    fetch(receiptPrintUrl, { method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }, body: JSON.stringify(payload) })
        .then(async response => {
            const result = await response.json().catch(() => ({}));
            if (!response.ok) {
                const error = new Error(result.message || 'Transaksi tidak dapat dicetak.');
                error.status = response.status;
                error.transactionSaved = result.transaction_saved === true;
                throw error;
            }
            if (!result.success) {
                const error = new Error(result.message || 'Printer kasir tidak dapat mencetak struk.');
                error.status = 503;
                throw error;
            }
            return result;
        })
        .then(result => {
            payload.items.forEach(({ product_id, qty }) => {
                const product = products.find(item => item.id === product_id);
                if (product) product.stock = Math.max(0, product.stock - qty);
            });
            status.className = 'receipt-status is-visible';
            status.textContent = result.message || 'Transaksi tersimpan dan struk berhasil dicetak.';
            resetTransaction();
        })
        .catch(error => {
            status.className = 'receipt-status is-visible is-error';
            if (error.status && error.status < 500) {
                status.textContent = `${error.message} Struk tidak dicetak; keranjang tetap tersimpan.`;
                return;
            }
            if (error.status && !error.transactionSaved) {
                status.textContent = `${error.message} Transaksi belum tersimpan; keranjang tetap tersimpan.`;
                return;
            }
            renderReceipt();
            window.print();
            status.textContent = `${error.message} Dialog cetak browser dibuka sebagai cadangan.`;
            resetTransaction();
        })
        .finally(() => { button.disabled = false; button.textContent = 'BAYAR & CETAK'; });
});
document.addEventListener('keydown', event => { if (event.key === 'F2') { event.preventDefault(); $('searchProduct').focus(); } if (event.key === 'F4') { event.preventDefault(); $('payment').focus(); } if (event.key === 'Escape') $('cancelButton').click(); });
setTransactionMeta(); renderCart();
</script>
@endsection