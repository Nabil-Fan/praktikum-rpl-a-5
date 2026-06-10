<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\FoodListingController;
use App\Http\Controllers\Api\MerchantController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes — EcoEats Mobile (User)
|--------------------------------------------------------------------------
| Semua route di sini otomatis dapat prefix /api
| Prefix v1 digunakan untuk versioning
*/

Route::prefix('v1')->group(function () {

    // ── Public Routes (tidak perlu login) ──────────────────────────────
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login',    [AuthController::class, 'login']);

    // ── Protected Routes (wajib login, sertakan Bearer token) ──────────
    Route::middleware('auth:sanctum')->group(function () {

        // Auth
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me',      [AuthController::class, 'me']);

        // Profil user
        Route::get('/user/profile',          [UserController::class, 'showProfile']);
        Route::put('/user/profile',          [UserController::class, 'updateProfile']);
        Route::put('/user/update-password',  [UserController::class, 'updatePassword']);

        // Kategori
        Route::get('/categories', [CategoryController::class, 'index']);

        // Food Listings — katalog makanan surplus
        Route::get('/food-listings',       [FoodListingController::class, 'index']);
        Route::get('/food-listings/{id}',  [FoodListingController::class, 'show']);

        // Merchants — data merchant dan lokasi untuk peta
        Route::get('/merchants',       [MerchantController::class, 'index']);
        Route::get('/merchants/{id}',  [MerchantController::class, 'show']);

        // Orders — pemesanan, riwayat, dan detail pesanan
        Route::post('/orders',       [OrderController::class, 'store']);
        Route::get('/orders',        [OrderController::class, 'index']);
        Route::get('/orders/{id}',   [OrderController::class, 'show']);
    });
});
