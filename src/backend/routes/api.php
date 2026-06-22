<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\FoodListingController;
use App\Http\Controllers\Api\MerchantController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\UserController; // BARU: untuk fitur edit profile & ganti password

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

        // User Profile — BARU: edit profile & ganti password
        Route::get('/user/profile',         [UserController::class, 'showProfile']);
        Route::put('/user/profile',         [UserController::class, 'updateProfile']);
        Route::put('/user/update-password', [UserController::class, 'updatePassword']);

        // Categories
        Route::get('/categories', [CategoryController::class, 'index']);

        // Food Listings
        Route::get('/food-listings',       [FoodListingController::class, 'index']);
        Route::get('/food-listings/{id}',  [FoodListingController::class, 'show']);

        // Orders
        Route::get('/orders',                                  [OrderController::class,   'index']);
        Route::post('/orders',                                 [OrderController::class,   'store']);
        Route::get('/orders/{id}',                             [OrderController::class,   'show']);
        Route::post('/orders/{id}/init-payment',               [OrderController::class,   'initPayment']);
        Route::post('/orders/{orderId}/payment-proof',         [PaymentController::class, 'uploadProof']);

        // Merchants — dikerjakan di branch berikutnya
        // Route::get('/merchants',      [MerchantController::class, 'index']);
        // Route::get('/merchants/{id}', [MerchantController::class, 'show']);
    });
});
