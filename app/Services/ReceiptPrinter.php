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
            $printer->text("Jl. Contoh No. 123 - Jember\nTelp. 0812-xxxx-xxxx\n");
            $printer->text(str_repeat('-', 42) . "\n");

            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text('No. Transaksi: ' . $sale->transaction_number . "\n");
            $printer->text('Tanggal: ' . $sale->sold_at->format('d/m/Y H:i:s') . "\n");
            $printer->text('Pelanggan: ' . $sale->customer . "\n");
            $printer->text(str_repeat('-', 42) . "\n");

            foreach ($sale->items as $item) {
                $printer->text($item->product_name . "\n");
                $this->printRow($printer, $item->quantity . ' x ' . $money((float) $item->unit_price), $money((float) $item->line_total));
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
            $this->printRow($printer, $sale->payment_method, $money((float) $sale->paid));
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
        $label = substr($label, 0, max(1, $width - strlen($value) - 1));
        $printer->text($label . str_repeat(' ', max(1, $width - strlen($label) - strlen($value))) . $value . "\n");
    }
}