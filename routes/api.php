<?php

use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\MobileStoreController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthenticatedSessionController::class, 'login']);
Route::post('register', [RegisteredUserController::class, 'register'])
    ->middleware('throttle:5,1');

Route::middleware(['auth:sanctum', 'active'])->group(function () {
    Route::get('/user', [AuthenticatedSessionController::class, 'user']);
    Route::post('/logout', [AuthenticatedSessionController::class, 'logout']);
    Route::middleware('role:admin,kasir')->group(function () {
        Route::get('/dashboard', [MobileStoreController::class, 'dashboard']);
        Route::get('/products', [MobileStoreController::class, 'products']);
        Route::get('/transactions', [MobileStoreController::class, 'transactions']);
        Route::post('/transactions', [MobileStoreController::class, 'storeTransaction'])
            ->middleware('throttle:10,1');
    });
});

Route::apiResource('books', BookController::class);
