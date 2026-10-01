<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StrukController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\TransactionHistoryController;

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('products', ProductController::class);

    Route::get('/admin', function () {
        return view('admin');
    })->name('admin.dashboard');
});

Route::get('/kasir', [KasirController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('kasir.index');
Route::get('/transaksi', [TransactionHistoryController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('transactions.index');
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/struk', [StrukController::class, 'cetak'])
    ->middleware(['auth', 'verified', 'throttle:10,1'])
    ->name('struk.cetak');

require __DIR__.'/auth.php';
