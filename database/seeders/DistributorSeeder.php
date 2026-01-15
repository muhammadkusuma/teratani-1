<?php

namespace Database\Seeders;

use App\Models\Distributor;
use App\Models\Toko;
use Illuminate\Database\Seeder;

class DistributorSeeder extends Seeder
{
    public function run(): void
    {
        $tokos = Toko::all();

        foreach ($tokos as $toko) {
            // Create 2-3 distributors per store
            $count = rand(2, 3);
            
            for ($i = 1; $i <= $count; $i++) {
                $distributorNumber = Distributor::count() + 1;
                
                Distributor::create([
                    'id_toko' => $toko->id_toko,
                    'kode_distributor' => 'DIST' . str_pad($distributorNumber, 3, '0', STR_PAD_LEFT),
                    'nama_distributor' => $this->getDistributorName($distributorNumber),
                    'nama_perusahaan' => $this->getCompanyName($distributorNumber),
                    'alamat' => 'Jl. Distributor No. ' . ($distributorNumber * 10),
                    'kota' => $this->getCity($distributorNumber),
                    'provinsi' => 'Jawa Timur',
                    'kode_pos' => '6514' . rand(1, 9),
                    'no_telp' => '0341-' . rand(100000, 999999),
                    'email' => 'distributor' . $distributorNumber . '@example.com',
                    'nama_kontak' => $this->getContactName($distributorNumber),
                    'no_hp_kontak' => '0812' . rand(10000000, 99999999),
                    'npwp' => sprintf('%02d.%03d.%03d.%d-%03d.%03d', 
                        rand(1, 99), rand(1, 999), rand(1, 999), 
                        rand(1, 9), rand(1, 999), rand(1, 999)),
                    'keterangan' => 'Distributor ' . $this->getDistributorName($distributorNumber),
                    'is_active' => rand(0, 10) > 1, // 90% active
                ]);
            }
        }
    }

    private function getDistributorName($number)
    {
        $names = [
            'CV Maju Jaya',
            'PT Sejahtera Abadi',
            'UD Berkah Sentosa',
            'CV Sumber Rezeki',
            'PT Cahaya Terang',
            'UD Harapan Baru',
            'CV Karya Mandiri',
            'PT Sukses Makmur',
            'UD Rizki Barokah',
        ];
        
        return $names[($number - 1) % count($names)];
    }

    private function getCompanyName($number)
    {
        $companies = [
            'PT Maju Jaya Abadi',
            'PT Sejahtera Sentosa Tbk',
            'UD Berkah Sentosa Mandiri',
            'PT Sumber Rezeki Indonesia',
            'PT Cahaya Terang Gemilang',
            'UD Harapan Baru Sejahtera',
            'PT Karya Mandiri Sukses',
            'PT Sukses Makmur Jaya',
            'UD Rizki Barokah Sentosa',
        ];
        
        return $companies[($number - 1) % count($companies)];
    }

    private function getCity($number)
    {
        $cities = ['Malang', 'Surabaya', 'Sidoarjo', 'Pasuruan', 'Blitar', 'Kediri'];
        return $cities[($number - 1) % count($cities)];
    }

    private function getContactName($number)
    {
        $names = [
            'Budi Santoso',
            'Siti Aminah',
            'Ahmad Fauzi',
            'Dewi Lestari',
            'Eko Prasetyo',
            'Fitri Handayani',
            'Gunawan',
            'Hesti Wulandari',
            'Irfan Hakim',
        ];
        
        return $names[($number - 1) % count($names)];
    }
}
