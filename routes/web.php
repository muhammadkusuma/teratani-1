<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\Owner\BisnisController;
use App\Http\Controllers\Owner\DistributorController;
use App\Http\Controllers\Owner\KaryawanController;
use App\Http\Controllers\Owner\GudangController;
use App\Http\Controllers\Owner\RiwayatStokController;
use App\Http\Controllers\Owner\PengeluaranController;
use App\Http\Controllers\Owner\PendapatanPasifController;
use App\Http\Controllers\Owner\KasirController;
use App\Http\Controllers\Owner\PelangganController;
use App\Http\Controllers\Owner\PerusahaanController;
use App\Http\Controllers\Owner\ProdukController;
use App\Http\Controllers\Owner\ProfileController;
use App\Http\Controllers\Owner\StokController;
use App\Http\Controllers\Owner\TokoController as OwnerTokoController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Owner\UserController as OwnerUserController;
use App\Http\Controllers\Owner\PembelianController;
use App\Http\Middleware\EnsureSuperAdmin;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;

Route::get('/redis-test', function () {
    Cache::put('test', 'Laravel + Redis OK', 60);
    return Cache::get('test');
});

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

        Route::get('/perusahaan', [PerusahaanController::class, 'index'])->name('perusahaan.index');
        Route::get('/perusahaan/edit', [PerusahaanController::class, 'edit'])->name('perusahaan.edit');
        Route::put('/perusahaan', [PerusahaanController::class, 'update'])->name('perusahaan.update');

        Route::resource('toko', OwnerTokoController::class)->except('show');
        Route::get('/toko/select/{id}', [OwnerTokoController::class, 'select'])->name('toko.select');

        Route::resource('toko.produk', ProdukController::class);
        Route::resource('toko.pembelian', PembelianController::class);

        // Customer piutang routes (must come before resource routes to avoid conflicts)
        Route::prefix('pelanggan')->name('pelanggan.')->group(function () {
            Route::get('/piutang', [PelangganController::class, 'piutangIndex'])->name('piutang.index');
            Route::get('/piutang/create', [PelangganController::class, 'piutangCreate'])->name('piutang.create');
            Route::post('/piutang', [PelangganController::class, 'piutangStore'])->name('piutang.store');
            Route::get('/piutang/{id}/edit', [PelangganController::class, 'piutangEdit'])->name('piutang.edit');
            Route::put('/piutang/{id}', [PelangganController::class, 'piutangUpdate'])->name('piutang.update');
            Route::delete('/piutang/{id}', [PelangganController::class, 'piutangDestroy'])->name('piutang.destroy');
        });

        // Customer resource routes
        Route::resource('pelanggan', PelangganController::class);

        // Distributor hutang routes (must come before resource routes to avoid conflicts)
        Route::prefix('distributor')->name('distributor.')->group(function () {
            Route::get('/hutang', [DistributorController::class, 'hutangIndex'])->name('hutang.index');
            Route::get('/hutang/create', [DistributorController::class, 'hutangCreate'])->name('hutang.create');
            Route::post('/hutang', [DistributorController::class, 'hutangStore'])->name('hutang.store');
            Route::get('/hutang/{id}/edit', [DistributorController::class, 'hutangEdit'])->name('hutang.edit');
            Route::put('/hutang/{id}', [DistributorController::class, 'hutangUpdate'])->name('hutang.update');
            Route::delete('/hutang/{id}', [DistributorController::class, 'hutangDestroy'])->name('hutang.destroy');
        });
        
        // Distributor resource routes
        Route::resource('distributor', DistributorController::class);

        Route::resource('karyawan', KaryawanController::class);

        Route::resource('pengeluaran', PengeluaranController::class);

        Route::resource('pendapatan_pasif', PendapatanPasifController::class);

        
        Route::resource('users', OwnerUserController::class);

        Route::resource('kategori', KategoriController::class);
        Route::resource('satuan', SatuanController::class);

        // Route::resource generates index, store, update, destroy
        // Explicit routes removed to avoid name collision

        Route::resource('gudang', GudangController::class)->only(['index', 'show']);
        Route::get('/riwayat-stok', [RiwayatStokController::class, 'index'])->name('riwayat-stok.index');
        Route::get('/riwayat-stok/create', [RiwayatStokController::class, 'create'])->name('riwayat-stok.create');
        Route::post('/riwayat-stok', [RiwayatStokController::class, 'store'])->name('riwayat-stok.store');

        Route::get('/stok', [StokController::class, 'index'])->name('stok.index');
        Route::get('/stok/tambah', [StokController::class, 'tambah'])->name('stok.tambah');
        Route::post('/stok/tambah', [StokController::class, 'store'])->name('stok.store');

        Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.index');
        Route::post('/kasir', [KasirController::class, 'store'])->name('kasir.store');
        Route::get('/kasir/riwayat', [KasirController::class, 'riwayat'])->name('kasir.riwayat');
        Route::get('/kasir/ajax-search', [KasirController::class, 'searchProduk'])->name('kasir.search');
        Route::get('/kasir/cetak/{id}', [KasirController::class, 'print'])->name('kasir.print');
        Route::get('/kasir/cetak-faktur/{id}', [KasirController::class, 'cetakFaktur'])->name('kasir.cetak-faktur');

        Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
        Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
        Route::get('/profile/edit-password', [ProfileController::class, 'editPassword'])->name('profile.edit-password'); // Keep for backward compatibility/redirect
        Route::put('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    });

    Route::middleware(EnsureSuperAdmin::class)->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('users', UserController::class);
        Route::resource('settings', SettingController::class);
    });
});
