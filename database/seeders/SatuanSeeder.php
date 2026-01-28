<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Satuan;

class SatuanSeeder extends Seeder
{
    public function run(): void
    {
        // Satuan Kecil (untuk Eceran/Retail)
        $satuanKecil = [
            'Pcs',      // Pieces/Buah
            'Botol',    // Botol
            'Kg',       // Kilogram
            'Gram',     // Gram
            'Liter',    // Liter
            'Ml',       // Mililiter
            'Meter',    // Meter
            'Cm',       // Centimeter
            'Lembar',   // Lembar/Sheet
            'Biji',     // Biji/Butir
            'Sachet',   // Sachet
            'Pail',     // Pail/Ember
            'Kaleng',   // Kaleng/Can
        ];

        // Satuan Besar (untuk Grosir/Wholesale)
        $satuanBesar = [
            'Box',      // Box/Kotak
            'Dus',      // Dus
            'Karung',   // Karung/Sak
            'Bal',      // Bal/Bale
            'Roll',     // Roll/Gulungan
            'Kontainer',// Container
            'Pallet',   // Pallet
            'Koli',     // Koli
            'Lusin',    // Dozen
            'Krat',     // Krat
            'Ikat',     // Ikat/Bundle
            'Galon',    // Galon
        ];

        // Insert Satuan Kecil
        foreach ($satuanKecil as $unit) {
            Satuan::create([
                'nama_satuan' => $unit,
                'tipe' => 'kecil',
            ]);
        }

        // Insert Satuan Besar
        foreach ($satuanBesar as $unit) {
            Satuan::create([
                'nama_satuan' => $unit,
                'tipe' => 'besar',
            ]);
        }
    }
}
