<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PerusahaanSeeder extends Seeder
{
    public function run()
    {
        DB::table('perusahaan')->insert([
            'nama_perusahaan' => 'PT Toko Tani Sejahtera',
            'alamat' => 'Jl. Raya Pertanian No. 123',
            'kota' => 'Jakarta',
            'provinsi' => 'DKI Jakarta',
            'kode_pos' => '12345',
            'no_telp' => '021-12345678',
            'email' => 'info@tokotani.com',
            'website' => 'www.tokotani.com',
            'npwp' => '01.234.567.8-901.000',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
