<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Api\BookController;

Route::post('login', [AuthenticatedSessionController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthenticatedSessionController::class, 'user']);
    Route::post('/logout', [AuthenticatedSessionController::class, 'logout']);
});

Route::apiResource('books', BookController::class);