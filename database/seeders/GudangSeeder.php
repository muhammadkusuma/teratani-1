<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GudangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first company ID or default to 1 if not exists (though it should)
        $id_perusahaan = DB::table('perusahaan')->value('id_perusahaan');

        $gudangs = [
            ['nama_gudang' => 'Gudang 1', 'lokasi' => 'Utama'],
            ['nama_gudang' => 'Gudang 2', 'lokasi' => 'Cadangan'],
            ['nama_gudang' => 'Gudang 3', 'lokasi' => 'Tambahan'],
        ];

        foreach ($gudangs as $gudang) {
            DB::table('gudang')->insertOrIgnore([
                'nama_gudang' => $gudang['nama_gudang'],
                'lokasi' => $gudang['lokasi'],
                'id_perusahaan' => $id_perusahaan,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
