<?php

namespace App\Services;

use App\Models\Sale;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class ReceiptPrinter
{
    public function print(Sale $sale): void
    {
        $printer = new Printer(new WindowsPrintConnector('VSC80'));

        try {
            $money = static fn (float $amount): string => 'Rp ' . number_format(round($amount), 0, ',', '.');

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->selectPrintMode(Printer::MODE_EMPHASIZED | Printer::MODE_DOUBLE_HEIGHT);
            $printer->text("NesiaStore\n");
            $printer->selectPrintMode();
            $printer->text("Jl. Trans Seba No. 123 - Sabu Raijua\nTelp. 0812-xxxx-xxxx\n");
            $printer->text(str_repeat('-', 42) . "\n");

            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text('No. Transaksi: ' . $this->sanitizeText($sale->transaction_number) . "\n");
            $printer->text('Tanggal: ' . $sale->sold_at->format('d/m/Y H:i:s') . "\n");
            $printer->text('Pelanggan: ' . $this->sanitizeText($sale->customer) . "\n");
            $printer->text(str_repeat('-', 42) . "\n");

            foreach ($sale->items as $item) {
                $printer->text($this->sanitizeText((string) $item->product_name) . ' (' . $item->quantity . 'x)' . "\n");
                $this->printRow($printer, 'Harga', $money((float) $item->unit_price));
            }

            $printer->text(str_repeat('-', 42) . "\n");
            $this->printRow($printer, 'Subtotal', $money((float) $sale->subtotal));
            if ((float) $sale->discount_percent > 0) {
                $discount = round((float) $sale->subtotal * (float) $sale->discount_percent / 100, 2);
                $this->printRow($printer, 'Diskon (' . (float) $sale->discount_percent . '%)', '-' . $money($discount));
            }
            if ((float) $sale->discount_amount > 0) {
                $this->printRow($printer, 'Diskon tambahan', '-' . $money((float) $sale->discount_amount));
            }
            if ((float) $sale->tax > 0) {
                $this->printRow($printer, 'Pajak / PPN', $money((float) $sale->tax));
            }
            if ((float) $sale->other_fee > 0) {
                $this->printRow($printer, 'Biaya lain', $money((float) $sale->other_fee));
            }

            $printer->selectPrintMode(Printer::MODE_EMPHASIZED);
            $this->printRow($printer, 'TOTAL', $money((float) $sale->total));
            $printer->selectPrintMode();
            $this->printRow($printer, $this->sanitizeText((string) $sale->payment_method), $money((float) $sale->paid));
            $this->printRow($printer, 'Kembalian', $money((float) $sale->change));
            $printer->text(str_repeat('-', 42) . "\n");
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Terima kasih atas kunjungan Anda.\n\n");
            $printer->cut();
        } finally {
            $printer->close();
        }
    }

    private function printRow(Printer $printer, string $label, string $value): void
    {
        $width = 42;
        $label = $this->sanitizeText($label);
        $value = $this->sanitizeText($value);

        $labelWidth = max(0, $width - mb_strlen($value) - 1);
        $label = mb_strimwidth($label, 0, $labelWidth, '');
        $padding = max(1, $width - mb_strlen($label) - mb_strlen($value));

        $printer->text($label . str_repeat(' ', $padding) . $value . "\n");
    }

    private function sanitizeText(string $value): string
    {
        $value = str_replace(["\r\n", "\r", "\n"], ' ', $value);
        $value = preg_replace('/\s{2,}/', ' ', $value) ?? $value;

        return trim($value);
    }
}