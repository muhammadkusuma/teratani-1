<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Pupuk',
            'Pestisida',
            'Bibit',
            'Alat Pertanian',
        ];

        foreach ($categories as $category) {
            Kategori::create([
                'nama_kategori' => $category,
            ]);
        }
    }
}
