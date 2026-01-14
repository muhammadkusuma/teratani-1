<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StokToko;
use App\Models\Toko;
use App\Models\Produk;

class StokTokoSeeder extends Seeder
{
    public function run(): void
    {
        $tokos = Toko::all();
        $produks = Produk::all();

        foreach ($tokos as $toko) {
            foreach ($produks as $produk) {
                $stokBase = rand(10, 100);
                
                if ($toko->is_pusat) {
                    $stokFisik = $stokBase * 2;
                } else {
                    $stokFisik = $stokBase;
                }

                StokToko::create([
                    'id_toko' => $toko->id_toko,
                    'id_produk' => $produk->id_produk,
                    'stok_fisik' => $stokFisik,
                    'stok_minimal' => 10,
                    'lokasi_rak' => 'RAK-' . chr(65 + rand(0, 5)) . '-' . rand(1, 10),
                ]);
            }
        }
    }
}
