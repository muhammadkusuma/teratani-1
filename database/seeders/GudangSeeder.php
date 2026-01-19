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
        // Get the first company ID or default to 1 if not exists
        $id_perusahaan = DB::table('perusahaan')->value('id_perusahaan');
        $tokos = DB::table('toko')->get();

        if ($tokos->isEmpty()) {
            return;
        }

        foreach ($tokos as $toko) {
            $gudangs = [
                ['nama_gudang' => 'Gudang 1 - ' . $toko->nama_toko, 'lokasi' => 'Utama'],
                ['nama_gudang' => 'Gudang 2 - ' . $toko->nama_toko, 'lokasi' => 'Cadangan'],
                ['nama_gudang' => 'Gudang 3 - ' . $toko->nama_toko, 'lokasi' => 'Tambahan'],
            ];

            foreach ($gudangs as $gudang) {
                DB::table('gudang')->insert([
                    'nama_gudang' => $gudang['nama_gudang'],
                    'lokasi' => $gudang['lokasi'],
                    'id_toko' => $toko->id_toko,
                    'id_perusahaan' => $id_perusahaan,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
