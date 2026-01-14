<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Satuan;
use App\Models\Tenant;

class SatuanSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::where('kode_unik_tenant', 'tts001')->first();

        $units = [
            'Kg',
            'Liter',
            'Pcs',
            'Karung',
            'Botol',
            'Dus',
            'Kaleng',
            'Sachet',
            'Gram',
            'Ml',
        ];

        foreach ($units as $unit) {
            Satuan::create([
                'id_tenant' => $tenant->id_tenant,
                'nama_satuan' => $unit,
            ]);
        }
    }
}
