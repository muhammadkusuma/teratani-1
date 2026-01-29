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

Route::get('/version/download-db', function () {
    if (app()->bound('debugbar')) {
        app('debugbar')->disable();
    }
    $password = request('password');
    if ($password !== 'wiraganteng123!@#') {
        return response('Unauthorized', 401);
    }

    $dbName = config('database.connections.mysql.database');
    $dbUser = config('database.connections.mysql.username');
    $dbPass = config('database.connections.mysql.password');
    $dbHost = config('database.connections.mysql.host');
    $dbPort = config('database.connections.mysql.port');

    $filename = "backup-{$dbName}-" . date('Y-m-d_H-i-s') . ".sql";

    $headers = [
        'Content-Type' => 'application/octet-stream',
        'Content-Disposition' => "attachment; filename=\"{$filename}\"",
    ];

    return response()->stream(function () use ($dbName, $dbUser, $dbPass, $dbHost, $dbPort) {
        $command = "mysqldump --user=" . escapeshellarg($dbUser) . 
                   " --password=" . escapeshellarg($dbPass) . 
                   " --host=" . escapeshellarg($dbHost) . 
                   " --port=" . escapeshellarg($dbPort) . 
                   " " . escapeshellarg($dbName);
        
        $proc = popen($command, 'r');
        while (!feof($proc)) {
            echo fread($proc, 1024);
            flush();
        }
        pclose($proc);
    }, 200, $headers);
});

Route::get('/version', function () {
    $phpVersion = phpversion();
    $laravelVersion = app()->version();
    try {
        $dbVersion = DB::select('SELECT VERSION() as version')[0]->version;
        $dbName = DB::connection()->getDatabaseName();

        // Database Stats (Size & Rows)
        $stats = DB::select("
            SELECT 
                SUM(TABLE_ROWS) as total_rows, 
                SUM(DATA_LENGTH + INDEX_LENGTH) as total_size 
            FROM INFORMATION_SCHEMA.TABLES 
            WHERE TABLE_SCHEMA = ?
        ", [$dbName])[0];
        
        $totalRows = number_format($stats->total_rows ?? 0);
        $totalSizeMb = number_format(($stats->total_size ?? 0) / 1024 / 1024, 2);

        // Benchmark Read (Simple Select)
        $startRead = microtime(true);
        DB::select('SELECT 1');
        $readTime = round((microtime(true) - $startRead) * 1000, 2);

        // Benchmark Write (Transaction + Temp Table to avoid side effects)
        $startWrite = microtime(true);
        DB::transaction(function () {
            DB::statement('CREATE TEMPORARY TABLE IF NOT EXISTS _speed_test (id int)');
            DB::statement('INSERT INTO _speed_test VALUES (1)');
            DB::statement('DROP TEMPORARY TABLE _speed_test');
        });
        $writeTime = round((microtime(true) - $startWrite) * 1000, 2);

        // System Stats (Project Size & Disk)
        $projectPath = base_path();
        $projectSize = 'Unknown';
        try {
            // Using du -sh for human readable size of the project directory
            // 2>/dev/null to suppress permission errors if any
            $output = shell_exec("du -sh " . escapeshellarg($projectPath) . " 2>/dev/null");
            $projectSize = trim(explode("\t", $output)[0] ?? 'Unknown');
        } catch (\Exception $e) {
            $projectSize = 'Error';
        }

        $diskFree = disk_free_space($projectPath);
        $diskTotal = disk_total_space($projectPath);
        $diskFreeGb = number_format(($diskFree ?: 0) / 1024 / 1024 / 1024, 2);
        $diskTotalGb = number_format(($diskTotal ?: 0) / 1024 / 1024 / 1024, 2);
        $diskUsedPercent = $diskTotal > 0 ? round((($diskTotal - $diskFree) / $diskTotal) * 100, 1) : 0;

        // CPU & RAM Stats
        $cpuCores = trim(shell_exec("nproc"));
        $cpuLoad = sys_getloadavg();
        $loadAvg = isset($cpuLoad[0]) ? $cpuLoad[0] : 'N/A';
        
        // RAM & Swap
        $freeOut = shell_exec("free -m");
        $lines = explode("\n", trim($freeOut));
        $memTotal = $memUsed = '0';
        $swapTotal = $swapUsed = '0';
        
        foreach ($lines as $line) {
            if (strpos($line, 'Mem:') !== false) {
                $parts = preg_split('/\s+/', $line);
                $memTotal = $parts[1] ?? 0; 
                $memUsed = $parts[2] ?? 0;
            }
            if (strpos($line, 'Swap:') !== false) {
                $parts = preg_split('/\s+/', $line);
                $swapTotal = $parts[1] ?? 0;
                $swapUsed = $parts[2] ?? 0;
            }
        }
        
        $ramUsageMb = number_format((float)$memUsed);
        $ramTotalMb = number_format((float)$memTotal);
        $swapUsageMb = number_format((float)$swapUsed);
        $swapTotalMb = number_format((float)$swapTotal);

        // HDD/SSD Speed Benchmark (10MB Write/Read)
        $benchFile = storage_path('app/disk_bench_' . uniqid() . '.tmp');
        // Ensure directory exists
        if (!file_exists(dirname($benchFile))) {
            mkdir(dirname($benchFile), 0755, true);
        }

        $dataSizeMb = 10;
        $data = str_repeat("0", $dataSizeMb * 1024 * 1024);

        // Disk Write
        $startDiskWrite = microtime(true);
        file_put_contents($benchFile, $data);
        $endDiskWrite = microtime(true);
        $diskWriteTime = $endDiskWrite - $startDiskWrite;
        $diskWriteSpeedVal = ($diskWriteTime > 0) ? ($dataSizeMb / $diskWriteTime) : 0;

        // Disk Read
        $startDiskRead = microtime(true);
        file_get_contents($benchFile);
        $endDiskRead = microtime(true);
        $diskReadTime = $endDiskRead - $startDiskRead;
        $diskReadSpeedVal = ($diskReadTime > 0) ? ($dataSizeMb / $diskReadTime) : 0;

        // Cleanup
        if (file_exists($benchFile)) {
            unlink($benchFile);
        }

        $diskWriteSpeed = number_format($diskWriteSpeedVal, 2);
        $diskReadSpeed = number_format($diskReadSpeedVal, 2);

        return "
            <div style='font-family: monospace; line-height: 1.5;'>
                <strong>System Versions</strong><br>
                PHP: $phpVersion<br>
                Laravel: $laravelVersion<br>
                Database: $dbVersion<br>
                <br>
                <strong>Hardware Resources</strong><br>
                CPU Cores: $cpuCores<br>
                CPU Load (1m): $loadAvg<br>
                RAM Usage: $ramUsageMb MB / $ramTotalMb MB<br>
                Swap Usage: $swapUsageMb MB / $swapTotalMb MB<br>
                <br>
                <strong>Database Stats ($dbName)</strong><br>
                Total Rows: $totalRows<br>
                Total Size: $totalSizeMb MB<br>
                <br>
            <br>
                <strong>System Storage</strong><br>
                Project Size: <strong>$projectSize</strong><br>
                Disk Usage: $diskUsedPercent% ($diskFreeGb GB free of $diskTotalGb GB)<br>
                <br>
                <strong>Performance Benchmarks</strong><br>
                DB Read Latency (SELECT 1): {$readTime} ms<br>
                DB Write Latency (Temp Table): {$writeTime} ms<br>
                INFO: Disk Speed (HDD/SSD) - 10MB Sample<br>
                Disk Write Speed: {$diskWriteSpeed} MB/s<br>
                Disk Read Speed: {$diskReadSpeed} MB/s<br>
                <br>
                <button onclick=\"downloadDb()\">Download Database Backup</button>
                <script>
                    function downloadDb() {
                        var pass = prompt('Enter Password to Download Database:');
                        if (pass) {
                            window.location.href = '/version/download-db?password=' + encodeURIComponent(pass);
                        }
                    }
                </script>
            </div>
        ";
    } catch (\Exception $e) {
        return "Error gathering stats: " . $e->getMessage();
    }
});

Route::get('/', [AuthController::class, 'showLoginForm']);

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
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
        Route::get('toko/{toko}/pembelian/search', [PembelianController::class, 'searchProduk'])->name('toko.pembelian.search');
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
        Route::get('pelanggan/search', [PelangganController::class, 'searchPelanggan'])->name('pelanggan.search');
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
        Route::get('distributor/search', [DistributorController::class, 'searchDistributor'])->name('distributor.search');
        Route::resource('distributor', DistributorController::class);

        Route::resource('karyawan', KaryawanController::class);

        Route::resource('pengeluaran', PengeluaranController::class);

        Route::resource('pendapatan_pasif', PendapatanPasifController::class);

        
        
        Route::resource('users', OwnerUserController::class);

        Route::resource('kategori', KategoriController::class);
        Route::resource('satuan', SatuanController::class);

        // Gudang as nested resource under toko (separated per store)
        Route::resource('toko.gudang', GudangController::class);
        
        Route::resource('retur-penjualan', \App\Http\Controllers\Owner\ReturPenjualanController::class);
        Route::resource('retur-pembelian', \App\Http\Controllers\Owner\ReturPembelianController::class);

        Route::get('/riwayat-stok', [RiwayatStokController::class, 'index'])->name('riwayat-stok.index');
        Route::get('/riwayat-stok/create', [RiwayatStokController::class, 'create'])->name('riwayat-stok.create');
        Route::post('/riwayat-stok', [RiwayatStokController::class, 'store'])->name('riwayat-stok.store');

        Route::get('/stok', [StokController::class, 'index'])->name('stok.index');
        Route::get('/stok/tambah', [StokController::class, 'tambah'])->name('stok.tambah');
        Route::post('/stok/tambah', [StokController::class, 'store'])->name('stok.store');

        Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.index');
        Route::post('/kasir', [KasirController::class, 'store'])->name('kasir.store');
        Route::get('/kasir/riwayat', [KasirController::class, 'riwayat'])->name('kasir.riwayat');
        Route::get('/kasir/salin/{id}', [KasirController::class, 'salin'])->name('kasir.salin'); // New Route for Copy Transaction
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
