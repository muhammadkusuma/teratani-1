<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PerusahaanSeeder::class,  // Must run first
            UserSeeder::class,
            TokoSeeder::class,
            KategoriSeeder::class,
            SatuanSeeder::class,
            ProdukSeeder::class,
            PelangganSeeder::class,
            DistributorSeeder::class,
            StokTokoSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
