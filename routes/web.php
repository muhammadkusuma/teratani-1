<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\Owner\BisnisController;
// use App\Http\Controllers\Owner\DistributorController; // Non-Inti
use App\Http\Controllers\Owner\KasirController;
// use App\Http\Controllers\Owner\LaporanKeuanganController; // Non-Inti

/*
|--------------------------------------------------------------------------
| OWNER Controllers
|--------------------------------------------------------------------------
*/

// use App\Http\Controllers\Owner\MutasiController; // Non-Inti
use App\Http\Controllers\Owner\PelangganController;
// use App\Http\Controllers\Owner\PembelianController; // Non-Inti
// use App\Http\Controllers\Owner\PengeluaranController; // Non-Inti
// use App\Http\Controllers\Owner\PiutangController; // Non-Inti
use App\Http\Controllers\Owner\ProdukController;
use App\Http\Controllers\Owner\TokoController as OwnerTokoController;
use App\Http\Controllers\SatuanController;
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
Route::view('/', 'welcome');

Route::view('/fitur', 'landing.fitur');
Route::view('/kontak', 'landing.kontak');
Route::get('/harga', fn() => view('maintenance'))->name('harga');

// --- Halaman Landing Non-Esensial (Di-comment) ---
// Route::view('/studi-kasus', 'landing.studi-kasus');
// Route::view('/tentang-kami', 'landing.tentang');
// Route::view('/karir', 'landing.karir');
// Route::view('/privasi', 'landing.privasi');
// Route::view('/syarat-ketentuan', 'landing.syarat');

/*
|--------------------------------------------------------------------------
| Guest Routes (Auth)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | OWNER AREA (INTI: Faktur, Kasir, Produk, Pelanggan)
    |--------------------------------------------------------------------------
    */
    Route::prefix('owner')->name('owner.')->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'ownerIndex'])->name('dashboard');

        // Bisnis / Tenant (Setup Awal)
        Route::get('/bisnis/baru', [BisnisController::class, 'create'])->name('bisnis.create');
        Route::post('/bisnis/baru', [BisnisController::class, 'store'])->name('bisnis.store');

        // Toko (Manajemen Cabang - Diperlukan untuk Kasir)
        Route::resource('toko', OwnerTokoController::class)->except('show');
        Route::get('/toko/select/{id}', [OwnerTokoController::class, 'select'])->name('toko.select');

        // Produk per Toko (Diperlukan untuk Kasir)
        Route::resource('toko.produk', ProdukController::class);

        // Pelanggan (Diperlukan untuk Faktur)
        Route::resource('pelanggan', PelangganController::class);

        // Master Data (Kategori & Satuan - Diperlukan untuk Produk)
        Route::resource('kategori', KategoriController::class);
        Route::resource('satuan', SatuanController::class);

        // === FITUR UTAMA: KASIR & FAKTUR ===
        Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.index');
        Route::post('/kasir', [KasirController::class, 'store'])->name('kasir.store');
        Route::get('/kasir/riwayat', [KasirController::class, 'riwayat'])->name('kasir.riwayat');
        Route::get('/kasir/ajax-search', [KasirController::class, 'searchProduk'])->name('kasir.search');
        Route::get('/kasir/cetak/{id}', [KasirController::class, 'print'])->name('kasir.print');
        Route::get('/kasir/cetak-faktur/{id}', [KasirController::class, 'cetakFaktur'])->name('kasir.cetak-faktur');

        /* * --- FITUR NON-INTI (DI-COMMENT) ---
         * Fokus saat ini hanya pada core features (Faktur/Kasir).
         */

        // Mutasi Stok (Non-Inti)
        // Route::get('/ajax/mutasi-produk/{id_toko}', [MutasiController::class, 'getProdukByToko'])->name('mutasi.get-produk');
        // Route::post('/mutasi/{id}/terima', [MutasiController::class, 'terima'])->name('mutasi.terima');
        // Route::resource('mutasi', MutasiController::class);

        // Pengeluaran (Non-Inti)
        // Route::resource('pengeluaran', PengeluaranController::class)->except(['show', 'edit', 'update']);

        // Laporan Keuangan (Non-Inti)
        // Route::get('/laporan-keuangan', [LaporanKeuanganController::class, 'index'])->name('laporan.keuangan');

        // Pembelian & Distributor (Non-Inti)
        // Route::resource('pembelian', PembelianController::class);
        // Route::resource('distributor', DistributorController::class);

        // Piutang (Non-Inti - Fokus ke Faktur dulu)
        // Route::get('/piutang', [PiutangController::class, 'index'])->name('piutang.index');
        // Route::get('/piutang/{id}', [PiutangController::class, 'show'])->name('piutang.show');
        // Route::post('/piutang/{id}/bayar', [PiutangController::class, 'storePayment'])->name('piutang.storePayment');
    });

    /*
    |--------------------------------------------------------------------------
    | SUPER ADMIN (INTI: Tenant, User Internal, Log)
    |--------------------------------------------------------------------------
    */
    Route::middleware(EnsureSuperAdmin::class)->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Manajemen Akun Internal (Customer Care, dll)
        Route::resource('users', UserController::class);

        // Manajemen Database Toko
        Route::resource('tenants', TenantController::class);

        // Log Sistem
        Route::resource('settings', SettingController::class);
    });

    /*
    |--------------------------------------------------------------------------
    | CUSTOMER CARE (TODO: Perlu Implementasi Controller)
    |--------------------------------------------------------------------------
    */
    // Route::prefix('cc')->name('cc.')->group(function () {
    //     Route::get('/marketing', ...);
    //     Route::get('/billing', ...);
    //     Route::get('/support', ...);
    // });
});

/*
|--------------------------------------------------------------------------
| Global / AJAX
|--------------------------------------------------------------------------
*/
// Rute untuk cetak faktur (diakses dari public/landing jika diperlukan, atau dipindah ke dalam auth)
Route::get('/transaksi/{id}/faktur', [KasirController::class, 'cetakFaktur'])
    ->name('kasir.cetak-faktur');

Route::post('/ajax/kategori', [KategoriController::class, 'storeAjax'])
    ->name('ajax.kategori.store');

Route::post('/ajax/satuan', [SatuanController::class, 'storeAjax'])
    ->name('ajax.satuan.store');
