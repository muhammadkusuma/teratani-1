<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\Owner\BisnisController;
use App\Http\Controllers\Owner\KasirController;
use App\Http\Controllers\Owner\PelangganController;
use App\Http\Controllers\Owner\ProdukController;
use App\Http\Controllers\Owner\TokoController as OwnerTokoController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureSuperAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'showLoginForm']);

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('owner')->name('owner.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'ownerIndex'])->name('dashboard');

        Route::get('/bisnis/baru', [BisnisController::class, 'create'])->name('bisnis.create');
        Route::post('/bisnis/baru', [BisnisController::class, 'store'])->name('bisnis.store');

        Route::resource('toko', OwnerTokoController::class)->except('show');
        Route::get('/toko/select/{id}', [OwnerTokoController::class, 'select'])->name('toko.select');

        Route::resource('toko.produk', ProdukController::class);

        Route::resource('pelanggan', PelangganController::class);

        Route::resource('kategori', KategoriController::class);
        Route::resource('satuan', SatuanController::class);

        Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.index');
        Route::post('/kasir', [KasirController::class, 'store'])->name('kasir.store');
        Route::get('/kasir/riwayat', [KasirController::class, 'riwayat'])->name('kasir.riwayat');
        Route::get('/kasir/ajax-search', [KasirController::class, 'searchProduk'])->name('kasir.search');
        Route::get('/kasir/cetak/{id}', [KasirController::class, 'print'])->name('kasir.print');
        Route::get('/kasir/cetak-faktur/{id}', [KasirController::class, 'cetakFaktur'])->name('kasir.cetak-faktur');
    });

    Route::middleware(EnsureSuperAdmin::class)->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('users', UserController::class);
        Route::resource('tenants', TenantController::class);
        Route::resource('settings', SettingController::class);
    });
});
Route::get('/transaksi/{id}/faktur', [KasirController::class, 'cetakFaktur'])
    ->name('kasir.cetak-faktur');

Route::post('/ajax/kategori', [KategoriController::class, 'storeAjax'])
    ->name('ajax.kategori.store');

Route::post('/ajax/satuan', [SatuanController::class, 'storeAjax'])
    ->name('ajax.satuan.store');
