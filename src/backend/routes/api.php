<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Api\AuthController;
// use App\Http\Controllers\Api\FoodListingController;
// use App\Http\Controllers\Api\MerchantController;

/*
|--------------------------------------------------------------------------
| API Routes - EcoEats Mobile
|--------------------------------------------------------------------------
| Semua route di sini otomatis dapat prefix /api
| Tambahkan prefix v1 untuk versioning
*/

Route::prefix('v1')->group(function () {

    // ── Public Routes (tidak perlu login) ──────────────────────────────
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login',    [AuthController::class, 'login']);

    // ── Protected Routes (wajib login, sertakan token) ─────────────────
    Route::middleware('auth:sanctum')->group(function () {

        // Auth
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me',      [AuthController::class, 'me']);

        // Food Listings — katalog makanan (dikerjakan Alena)
        // Route::get('/food-listings',      [FoodListingController::class, 'index']);
        // Route::get('/food-listings/{id}', [FoodListingController::class, 'show']);

        // Merchants — data merchant & lokasi (dikerjakan Nabil)
        // Route::get('/merchants',      [MerchantController::class, 'index']);
        // Route::get('/merchants/{id}', [MerchantController::class, 'show']);

        // Orders — pemesanan (nanti)
        // Route::get('/orders',        [OrderController::class, 'index']);
        // Route::post('/orders',       [OrderController::class, 'store']);
        // Route::get('/orders/{id}',   [OrderController::class, 'show']);
    });
});