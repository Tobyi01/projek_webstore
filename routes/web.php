<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StrukController;
use App\Http\Controllers\KasirController;

Route::resource('products', ProductController::class);
Route::get('/kasir', [KasirController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('kasir.index');
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

Route::get('/struk', [StrukController::class, 'cetak'])->name('struk.cetak');

require __DIR__.'/auth.php';
