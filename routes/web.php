<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Owner\BisnisController;
use App\Http\Controllers\Owner\ProdukController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Owner\TokoController as OwnerTokoController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureSuperAdmin;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get("/", function () {
    return view('welcome');
});

Route::get('/fitur', function () {return view('landing.fitur');});
Route::get('/harga', function () {return view('landing.harga');});
Route::get('/studi-kasus', function () {return view('landing.studi-kasus');});
Route::get('/tentang-kami', function () {return view('landing.tentang');});
Route::get('/karir', function () {return view('landing.karir');});
Route::get('/kontak', function () {return view('landing.kontak');});
Route::get('/privasi', function () {return view('landing.privasi');});
Route::get('/syarat-ketentuan', function () {return view('landing.syarat');});

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Logout bisa diakses semua user login
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // --- OWNER / TOKO DASHBOARD ---
    Route::prefix('owner')->name('owner.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'ownerIndex'])->name('dashboard');
    });

    // --- OWNER AREA ---
    Route::prefix('owner')->name('owner.')->group(function () {

        // 1. Route Khusus Pembuatan Bisnis (Tenant)
        Route::get('/bisnis/baru', [BisnisController::class, 'create'])->name('bisnis.create');
        Route::post('/bisnis/baru', [BisnisController::class, 'store'])->name('bisnis.store');

        // 2. Dashboard & Toko (Yang sudah ada)
        Route::get('/dashboard', [DashboardController::class, 'ownerIndex'])->name('dashboard');

        // ... route toko lainnya (copy dari file sebelumnya) ...
        Route::get('/toko', [OwnerTokoController::class, 'index'])->name('toko.index');
        Route::get('/toko/create', [OwnerTokoController::class, 'create'])->name('toko.create');
        Route::post('/toko', [OwnerTokoController::class, 'store'])->name('toko.store');
        Route::get('/toko/{id}/edit', [OwnerTokoController::class, 'edit'])->name('toko.edit');
        Route::put('/toko/{id}', [OwnerTokoController::class, 'update'])->name('toko.update');
        Route::delete('/toko/{id}', [OwnerTokoController::class, 'destroy'])->name('toko.destroy');
        Route::get('/toko/select/{id}', [OwnerTokoController::class, 'select'])->name('toko.select');

        Route::resource('toko.produk', ProdukController::class);

        Route::resource('mutasi', \App\Http\Controllers\Owner\MutasiController::class);
    });

    // --- SUPERADMIN DASHBOARD ---
    // Menggunakan Class Middleware 'EnsureSuperAdmin'
    Route::middleware([EnsureSuperAdmin::class])->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('users', UserController::class)->names('users');
        Route::resource('tenants', TenantController::class);
        Route::resource('settings', SettingController::class);
    });

});


Route::post('/ajax/kategori', [App\Http\Controllers\KategoriController::class, 'storeAjax'])->name('ajax.kategori.store');
Route::post('/ajax/satuan', [App\Http\Controllers\SatuanController::class, 'storeAjax'])->name('ajax.satuan.store');