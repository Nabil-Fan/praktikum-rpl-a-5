<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController     as AdminDashboard;
use App\Http\Controllers\Admin\MerchantVerificationController;
use App\Http\Controllers\Admin\UserController          as AdminUserController;
use App\Http\Controllers\Admin\FoodListingController   as AdminFoodListingController;
use App\Http\Controllers\Admin\AccountController       as AdminAccountController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Merchant\DashboardController  as MerchantDashboard;
use App\Http\Controllers\Merchant\FoodListingController as MerchantFoodListingController;
use App\Http\Controllers\Merchant\ProfileController    as MerchantProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes — EcoEats
|--------------------------------------------------------------------------
*/

// ── ROOT ────────────────────────────────────────────────────────────────
Route::get('/', function () {
    return redirect()->route('login');
});

// ── AUTH: User Biasa ─────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login',  [LoginController::class,    'showUserLogin'])->name('login');
    Route::post('/login', [LoginController::class,    'loginUser'])->name('login.post');
    // Register
    Route::get('/register',  [RegisterController::class, 'showUserRegister'])->name('register');
    Route::post('/register', [RegisterController::class, 'registerUser'])->name('register.post');
});

// ── AUTH: Mitra Merchant ─────────────────────────────────────────────────
Route::middleware('guest')->prefix('merchant')->name('merchant.')->group(function () {
    // Login
    Route::get('/login',  [LoginController::class,    'showMerchantLogin'])->name('login');
    Route::post('/login', [LoginController::class,    'loginMerchant'])->name('login.post');
    // Register
    Route::get('/register',  [RegisterController::class, 'showMerchantRegister'])->name('register');
    Route::post('/register', [RegisterController::class, 'registerMerchant'])->name('register.post');
});

// ── AUTH: Admin ──────────────────────────────────────────────────────────
Route::middleware('guest')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/login',  [LoginController::class, 'showAdminLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'loginAdmin'])->name('login.post');
});

// ── LOGOUT ───────────────────────────────────────────────────────────────
Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ── DASHBOARD: User Biasa ────────────────────────────────────────────────
Route::middleware(['auth', 'role:user'])->prefix('home')->name('user.')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.user');
    })->name('dashboard');
});

// ── DASHBOARD & FITUR: Mitra Merchant ───────────────────────────────────
Route::middleware(['auth', 'role:merchant'])->prefix('merchant')->name('merchant.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [MerchantDashboard::class, 'index'])->name('dashboard');

    // Profil usaha
    Route::get('/profile/edit', [MerchantProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile',      [MerchantProfileController::class, 'update'])->name('profile.update');

    // Food Listings (CRUD)
    // Menghasilkan: listings.index, listings.create, listings.store,
    //               listings.edit, listings.update, listings.destroy
    Route::resource('listings', MerchantFoodListingController::class)->except(['show']);
});

// ── DASHBOARD & FITUR: Admin ─────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // Verifikasi Merchant
    Route::get('/merchants',                              [MerchantVerificationController::class, 'index'])->name('merchants.index');
    Route::get('/merchants/{merchant}',                   [MerchantVerificationController::class, 'show'])->name('merchants.show');
    Route::post('/merchants/{merchant}/approve',          [MerchantVerificationController::class, 'approve'])->name('merchants.approve');
    Route::post('/merchants/{merchant}/reject',           [MerchantVerificationController::class, 'reject'])->name('merchants.reject');
    // Reset verifikasi merchant ke pending
    Route::post('/merchants/{merchant}/reset',            [AdminAccountController::class, 'resetVerification'])->name('merchants.reset');

    // Manajemen User
    Route::get('/users',                   [AdminUserController::class,   'index'])->name('users.index');
    Route::delete('/users/{user}',         [AdminUserController::class,   'destroy'])->name('users.destroy');
    Route::post('/users/{id}/restore',     [AdminUserController::class,   'restore'])->name('users.restore');
    // Toggle aktif/nonaktif (endpoint tunggal menggantikan destroy + restore terpisah)
    Route::post('/users/{user}/toggle',    [AdminAccountController::class,'toggleActive'])->name('users.toggle');

    // Tambah akun manual
    Route::get('/accounts/create',         [AdminAccountController::class, 'create'])->name('accounts.create');
    Route::post('/accounts',               [AdminAccountController::class, 'store'])->name('accounts.store');

    // Manajemen Food Listing (read-only + force-delete)
    Route::get('/food-listings',                    [AdminFoodListingController::class, 'index'])->name('food-listings.index');
    Route::delete('/food-listings/{foodListing}',   [AdminFoodListingController::class, 'destroy'])->name('food-listings.destroy');

        // Kategori
    Route::get('/categories',                  [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories',                 [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}',       [AdminCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}',    [AdminCategoryController::class, 'destroy'])->name('categories.destroy');
});