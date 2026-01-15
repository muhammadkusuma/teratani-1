<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\Owner\BisnisController;
use App\Http\Controllers\Owner\DistributorController;
use App\Http\Controllers\Owner\KaryawanController;
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

        Route::get('/perusahaan', [PerusahaanController::class, 'index'])->name('perusahaan.index');
        Route::get('/perusahaan/edit', [PerusahaanController::class, 'edit'])->name('perusahaan.edit');
        Route::put('/perusahaan', [PerusahaanController::class, 'update'])->name('perusahaan.update');

        Route::resource('toko', OwnerTokoController::class)->except('show');
        Route::get('/toko/select/{id}', [OwnerTokoController::class, 'select'])->name('toko.select');

        Route::resource('toko.produk', ProdukController::class);

        Route::resource('pelanggan', PelangganController::class);

        Route::resource('distributor', DistributorController::class);

        Route::resource('karyawan', KaryawanController::class);

        Route::resource('pengeluaran', PengeluaranController::class);

        Route::resource('pendapatan_pasif', PendapatanPasifController::class);
        
        Route::resource('users', OwnerUserController::class);

        Route::resource('kategori', KategoriController::class);
        Route::resource('satuan', SatuanController::class);

        Route::get('/satuan', [SatuanController::class, 'index'])->name('satuan.index');
        Route::post('/satuan', [SatuanController::class, 'store'])->name('satuan.store');
        Route::put('/satuan/{id}', [SatuanController::class, 'update'])->name('satuan.update');
        Route::delete('/satuan/{id}', [SatuanController::class, 'destroy'])->name('satuan.destroy');

        Route::get('/stok', [StokController::class, 'index'])->name('stok.index');
        Route::get('/stok/tambah', [StokController::class, 'tambah'])->name('stok.tambah');
        Route::post('/stok/tambah', [StokController::class, 'store'])->name('stok.store');

        Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.index');
        Route::post('/kasir', [KasirController::class, 'store'])->name('kasir.store');
        Route::get('/kasir/riwayat', [KasirController::class, 'riwayat'])->name('kasir.riwayat');
        Route::get('/kasir/ajax-search', [KasirController::class, 'searchProduk'])->name('kasir.search');
        Route::get('/kasir/cetak/{id}', [KasirController::class, 'print'])->name('kasir.print');
        Route::get('/kasir/cetak-faktur/{id}', [KasirController::class, 'cetakFaktur'])->name('kasir.cetak-faktur');

        Route::get('/profile/edit-password', [ProfileController::class, 'editPassword'])->name('profile.edit-password');
        Route::post('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    });

    Route::middleware(EnsureSuperAdmin::class)->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('users', UserController::class);
        Route::resource('settings', SettingController::class);
    });
});
