<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Satuan;

class SatuanSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            'Kg',
            'Liter',
            'Pcs',
            'Karung',
            'Botol',
        ];

        foreach ($units as $unit) {
            Satuan::create([
                'nama_satuan' => $unit,
            ]);
        }
    }
}
