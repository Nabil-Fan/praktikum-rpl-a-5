<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes — EcoEats
|--------------------------------------------------------------------------
*/

// -----------------------------------------------------------------------
// ROOT — redirect ke halaman login user
// -----------------------------------------------------------------------
Route::get('/', function () {
    return redirect()->route('login');
});

// -----------------------------------------------------------------------
// AUTH — User Biasa
// -----------------------------------------------------------------------
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showUserLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'loginUser'])->name('login.post');
});

// -----------------------------------------------------------------------
// AUTH — Mitra Merchant (portal terpisah sesuai FR-03)
// -----------------------------------------------------------------------
Route::middleware('guest')->prefix('merchant')->name('merchant.')->group(function () {
    Route::get('/login', [LoginController::class, 'showMerchantLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'loginMerchant'])->name('login.post');
});

// -----------------------------------------------------------------------
// AUTH — Admin
// -----------------------------------------------------------------------
Route::middleware('guest')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [LoginController::class, 'showAdminLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'loginAdmin'])->name('login.post');
});

// -----------------------------------------------------------------------
// LOGOUT — semua role pakai satu route
// -----------------------------------------------------------------------
Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// -----------------------------------------------------------------------
// DASHBOARD — User Biasa
// -----------------------------------------------------------------------
Route::middleware(['auth', 'role:user'])->prefix('home')->name('user.')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.user');
    })->name('dashboard');

    // Tambahkan routes user lainnya di sini (katalog, pesanan, dll.)
});

// -----------------------------------------------------------------------
// DASHBOARD — Mitra Merchant
// -----------------------------------------------------------------------
Route::middleware(['auth', 'role:merchant'])->prefix('merchant')->name('merchant.')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.merchant');
    })->name('dashboard');

    // Tambahkan routes merchant lainnya di sini (kelola menu, pesanan, dll.)
});

// -----------------------------------------------------------------------
// DASHBOARD — Admin
// -----------------------------------------------------------------------
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.admin');
    })->name('dashboard');

    // Tambahkan routes admin lainnya di sini (verifikasi merchant, dll.)
});