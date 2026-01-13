<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\Owner\BisnisController;
use App\Http\Controllers\Owner\DistributorController;
use App\Http\Controllers\Owner\KasirController;
use App\Http\Controllers\Owner\LaporanKeuanganController;

/*
|--------------------------------------------------------------------------
| OWNER Controllers
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Owner\MutasiController;
use App\Http\Controllers\Owner\PelangganController;
use App\Http\Controllers\Owner\PembelianController;
use App\Http\Controllers\Owner\PengeluaranController;
use App\Http\Controllers\Owner\PiutangController;
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
Route::view('/studi-kasus', 'landing.studi-kasus');
Route::view('/tentang-kami', 'landing.tentang');
Route::view('/karir', 'landing.karir');
Route::view('/kontak', 'landing.kontak');
Route::view('/privasi', 'landing.privasi');
Route::view('/syarat-ketentuan', 'landing.syarat');

Route::get('/harga', fn() => view('maintenance'))->name('harga');

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
    | OWNER AREA
    |--------------------------------------------------------------------------
    */
    Route::prefix('owner')->name('owner.')->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'ownerIndex'])->name('dashboard');

        // Bisnis / Tenant
        Route::get('/bisnis/baru', [BisnisController::class, 'create'])->name('bisnis.create');
        Route::post('/bisnis/baru', [BisnisController::class, 'store'])->name('bisnis.store');

        // Toko
        Route::resource('toko', OwnerTokoController::class)->except('show');
        Route::get('/toko/select/{id}', [OwnerTokoController::class, 'select'])->name('toko.select');

        // Produk per Toko
        Route::resource('toko.produk', ProdukController::class);

        // Mutasi
        Route::get('/ajax/mutasi-produk/{id_toko}', [MutasiController::class, 'getProdukByToko'])
            ->name('mutasi.get-produk');
        Route::post('/mutasi/{id}/terima', [MutasiController::class, 'terima'])->name('mutasi.terima');
        Route::resource('mutasi', MutasiController::class);

        // Pelanggan
        Route::resource('pelanggan', PelangganController::class);

        // Kasir / POS
        Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.index');
        Route::post('/kasir', [KasirController::class, 'store'])->name('kasir.store');
        Route::get('/kasir/riwayat', [KasirController::class, 'riwayat'])->name('kasir.riwayat');
        Route::get('/kasir/ajax-search', [KasirController::class, 'searchProduk'])->name('kasir.search');
        Route::get('/kasir/cetak/{id}', [KasirController::class, 'print'])->name('kasir.print');
        Route::get('/kasir/cetak-faktur/{id}', [KasirController::class, 'cetakFaktur'])->name('kasir.cetak-faktur');

        // Pengeluaran
        Route::resource('pengeluaran', PengeluaranController::class)
            ->except(['show', 'edit', 'update']);

        // Laporan
        Route::get('/laporan-keuangan', [LaporanKeuanganController::class, 'index'])
            ->name('laporan.keuangan');

        // Master Data
        Route::resource('kategori', KategoriController::class);
        Route::resource('satuan', SatuanController::class);

        // Pembelian & Distributor
        Route::resource('pembelian', PembelianController::class);
        Route::resource('distributor', DistributorController::class);

        // Piutang
        Route::get('/piutang', [PiutangController::class, 'index'])->name('piutang.index');
        Route::get('/piutang/{id}', [PiutangController::class, 'show'])->name('piutang.show');
        Route::post('/piutang/{id}/bayar', [PiutangController::class, 'storePayment'])
            ->name('piutang.storePayment');
    });

    /*
    |--------------------------------------------------------------------------
    | SUPER ADMIN
    |--------------------------------------------------------------------------
    */
    Route::middleware(EnsureSuperAdmin::class)->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('users', UserController::class);
        Route::resource('tenants', TenantController::class);
        Route::resource('settings', SettingController::class);
    });
});

/*
|--------------------------------------------------------------------------
| Global / AJAX
|--------------------------------------------------------------------------
*/
Route::get('/transaksi/{id}/faktur', [KasirController::class, 'cetakFaktur'])
    ->name('kasir.cetak-faktur');

Route::post('/ajax/kategori', [KategoriController::class, 'storeAjax'])
    ->name('ajax.kategori.store');

Route::post('/ajax/satuan', [SatuanController::class, 'storeAjax'])
    ->name('ajax.satuan.store');
