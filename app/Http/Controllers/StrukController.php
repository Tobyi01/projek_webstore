<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\GdEscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class StrukController extends Controller
{
    function cetak() {
        try {
            $imagePath = public_path('images/pertamina2.png');

            if (!file_exists($imagePath)) {
                throw new \RuntimeException('Logo struk tidak ditemukan pada path: ' . $imagePath);
            }

            $connector = new WindowsPrintConnector("VSC80");
            $printer = new Printer($connector);

            try {
                if (file_exists($imagePath)) {
                    $image = GdEscposImage::load($imagePath, false);
                    $printer->bitImage($image);
                }
            } catch (\Throwable $e) {
                // Jika logo tidak valid untuk escpos, tetap lanjut cetak text saja
                $printer->text("[Logo tidak dapat dimuat]\n");
            }

            $printer->setFont(Printer::FONT_A);
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->selectPrintMode(Printer::MODE_EMPHASIZED | Printer::MODE_DOUBLE_HEIGHT);
            $printer->text("Nama Toko\n");
            $printer->selectPrintMode();
            $printer->text("Alamat Toko\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("Tanggal: " . date('Y-m-d H:i:s') . "\n");
            $printer->text("--------------------------------------------------\n");
            // Diulang
            $printer->setFont(Printer::FONT_B);
            $printer->text("Item 1    Rp 10.000\n");
            $printer->text("Item 2    Rp 15.000\n");

            $printer->setFont(Printer::FONT_A);
            $printer->text("------------------------------\n");
            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->text("Total   Rp 25.000\n");
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("\nTerima kasih atas kunjungan Anda!\n");

            // Potong kertas
            $printer->cut();

            // Tutup koneksi printer
            $printer->close();

            return response()->json(['success' => true, 'message' => 'Struk berhasil dicetak']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal mencetak struk: ' . $e->getMessage()], 500);
        }
    }
}
