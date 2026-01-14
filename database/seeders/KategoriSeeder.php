<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;
use App\Models\Tenant;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::where('kode_unik_tenant', 'tts001')->first();

        $categories = [
            'Pupuk',
            'Pestisida',
            'Bibit',
            'Alat Pertanian',
            'Herbisida',
            'Fungisida',
            'Insektisida',
            'Pakan Ternak',
        ];

        foreach ($categories as $category) {
            Kategori::create([
                'id_tenant' => $tenant->id_tenant,
                'nama_kategori' => $category,
            ]);
        }
    }
}
