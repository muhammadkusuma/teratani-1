<?php

namespace Database\Seeders;

use App\Models\Toko;
use Illuminate\Database\Seeder;

class TokoSeeder extends Seeder
{
    public function run(): void
    {
        Toko::create([
            'kode_toko' => 'TK001',
            'nama_toko' => 'Toko Tani Pusat',
            'alamat' => 'Jl. Raya Pertanian No. 123',
            'kota' => 'Malang',
            'no_telp' => '0341-123456',
            'info_rekening' => 'BCA 1234567890 a.n. Toko Tani',
            'is_pusat' => true,
            'is_active' => true,
        ]);

        Toko::create([
            'kode_toko' => 'TK002',
            'nama_toko' => 'Toko Tani Cabang Lawang',
            'alamat' => 'Jl. Raya Lawang No. 45',
            'kota' => 'Lawang',
            'no_telp' => '0341-234567',
            'info_rekening' => 'BCA 1234567890 a.n. Toko Tani',
            'is_pusat' => false,
            'is_active' => true,
        ]);

        Toko::create([
            'kode_toko' => 'TK003',
            'nama_toko' => 'Toko Tani Cabang Singosari',
            'alamat' => 'Jl. Raya Singosari No. 67',
            'kota' => 'Singosari',
            'no_telp' => '0341-345678',
            'info_rekening' => 'BCA 1234567890 a.n. Toko Tani',
            'is_pusat' => false,
            'is_active' => true,
        ]);
    }
}
