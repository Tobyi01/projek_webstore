<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { border: 1px solid #ccc; padding: 8px; }
        .total { font-weight: bold; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>INVOICE</h2>
        <p>No. Transaksi: {{ $invoice->transaction_number }}</p>
        <p>Tanggal: {{ $invoice->sold_at->format('d/m/Y H:i') }}</p>
        <p>Pelanggan: {{ $invoice->customer }}</p>
        <p>Metode pembayaran: {{ $invoice->payment_method }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
            <tr>
                <td>{{ $item->product_name }}</td>
                <td>Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                <td>{{ $item->quantity }}</td>
                <td>Rp {{ number_format($item->line_total, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="total">Subtotal:</td>
                <td class="total">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="3" class="total">Grand Total:</td>
                <td class="total">Rp {{ number_format($invoice->total, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="3" class="total">Dibayar:</td>
                <td class="total">Rp {{ number_format($invoice->paid, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="3" class="total">Kembalian:</td>
                <td class="total">Rp {{ number_format($invoice->change, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
