<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Toko;
use App\Models\Tenant;

class TokoSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::where('kode_unik_tenant', 'tts001')->first();

        Toko::create([
            'id_tenant' => $tenant->id_tenant,
            'kode_toko' => 'TKO-001',
            'nama_toko' => 'Toko Tani Pusat',
            'alamat' => 'Jl. Raya Pertanian No. 123, Malang',
            'kota' => 'Malang',
            'no_telp' => '0341-123456',
            'info_rekening' => 'BCA 1234567890 a.n. Toko Tani Sejahtera',
            'is_pusat' => true,
            'is_active' => true,
        ]);

        Toko::create([
            'id_tenant' => $tenant->id_tenant,
            'kode_toko' => 'TKO-002',
            'nama_toko' => 'Toko Tani Cabang Lawang',
            'alamat' => 'Jl. Raya Lawang No. 45, Lawang',
            'kota' => 'Lawang',
            'no_telp' => '0341-234567',
            'info_rekening' => 'BCA 1234567890 a.n. Toko Tani Sejahtera',
            'is_pusat' => false,
            'is_active' => true,
        ]);

        Toko::create([
            'id_tenant' => $tenant->id_tenant,
            'kode_toko' => 'TKO-003',
            'nama_toko' => 'Toko Tani Cabang Batu',
            'alamat' => 'Jl. Raya Batu No. 78, Batu',
            'kota' => 'Batu',
            'no_telp' => '0341-345678',
            'info_rekening' => 'BCA 1234567890 a.n. Toko Tani Sejahtera',
            'is_pusat' => false,
            'is_active' => true,
        ]);
    }
}
