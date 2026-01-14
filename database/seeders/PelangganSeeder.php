<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pelanggan;
use App\Models\Toko;

class PelangganSeeder extends Seeder
{
    public function run(): void
    {
        $tokoPusat = Toko::where('kode_toko', 'TK001')->first();

        $customers = [
            [
                'kode_pelanggan' => 'PLG-001',
                'nama_pelanggan' => 'Pak Tani Jaya',
                'wilayah' => 'Malang Kota',
                'no_hp' => '081234560001',
                'alamat' => 'Jl. Veteran No. 12, Malang',
                'limit_piutang' => 5000000,
            ],
            [
                'kode_pelanggan' => 'PLG-002',
                'nama_pelanggan' => 'Bu Sari Petani',
                'wilayah' => 'Lawang',
                'no_hp' => '081234560002',
                'alamat' => 'Jl. Raya Lawang No. 23, Lawang',
                'limit_piutang' => 3000000,
            ],
            [
                'kode_pelanggan' => 'PLG-003',
                'nama_pelanggan' => 'Kelompok Tani Makmur',
                'wilayah' => 'Batu',
                'no_hp' => '081234560003',
                'alamat' => 'Jl. Raya Batu No. 45, Batu',
                'limit_piutang' => 10000000,
            ],
            [
                'kode_pelanggan' => 'PLG-004',
                'nama_pelanggan' => 'Pak Budi Sawah',
                'wilayah' => 'Singosari',
                'no_hp' => '081234560004',
                'alamat' => 'Jl. Raya Singosari No. 67, Singosari',
                'limit_piutang' => 2000000,
            ],
            [
                'kode_pelanggan' => 'PLG-005',
                'nama_pelanggan' => 'Toko Tani Sejahtera',
                'wilayah' => 'Kepanjen',
                'no_hp' => '081234560005',
                'alamat' => 'Jl. Raya Kepanjen No. 89, Kepanjen',
                'limit_piutang' => 7000000,
            ],
            [
                'kode_pelanggan' => 'PLG-006',
                'nama_pelanggan' => 'Pak Agus Kebun',
                'wilayah' => 'Tumpang',
                'no_hp' => '081234560006',
                'alamat' => 'Jl. Raya Tumpang No. 34, Tumpang',
                'limit_piutang' => 4000000,
            ],
            [
                'kode_pelanggan' => 'PLG-007',
                'nama_pelanggan' => 'Bu Rina Hortikultura',
                'wilayah' => 'Pujon',
                'no_hp' => '081234560007',
                'alamat' => 'Jl. Raya Pujon No. 56, Pujon',
                'limit_piutang' => 3500000,
            ],
            [
                'kode_pelanggan' => 'PLG-008',
                'nama_pelanggan' => 'Koperasi Tani Maju',
                'wilayah' => 'Malang Kota',
                'no_hp' => '081234560008',
                'alamat' => 'Jl. Soekarno Hatta No. 78, Malang',
                'limit_piutang' => 15000000,
            ],
        ];

        foreach ($customers as $customer) {
            Pelanggan::create(array_merge($customer, [
                'id_toko' => $tokoPusat->id_toko,
            ]));
        }
    }
}
