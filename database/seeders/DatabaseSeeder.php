<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PerusahaanSeeder::class,  // Must run first
            TokoSeeder::class,        // Must run before Karyawan
            KaryawanSeeder::class,    // Must run before User to link accounts
            UserSeeder::class,        // Now runs after Karyawan
            KategoriSeeder::class,
            SatuanSeeder::class,
            // ProdukSeeder::class,
            // PelangganSeeder::class,
            // DistributorSeeder::class,
            // UtangPiutangDistributorSeeder::class,  // After Distributor
            // UtangPiutangPelangganSeeder::class,    // After Pelanggan
            // PengeluaranSeeder::class,
            // PendapatanPasifSeeder::class,
            // GudangSeeder::class,
            // StokTokoSeeder::class,
            // PembelianSeeder::class,
            // PenjualanSeeder::class,
            // SyncPendapatanFromPenjualanSeeder::class,
            // SettingSeeder::class,
        ]);
    }
}
