<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pelanggan;
use App\Models\Toko;
use Illuminate\Support\Facades\DB;

class PelangganSeeder extends Seeder
{
    public function run(): void
    {
        $tokoPusat = Toko::where('kode_toko', 'TK001')->first();

        $prefixes = ['Pak', 'Bu', 'Bapak', 'Ibu', 'Tuan', 'Nyonya', 'Sdr', 'Sdri'];
        $firstNames = ['Agus', 'Budi', 'Citra', 'Dewi', 'Eko', 'Fitri', 'Gunawan', 'Hadi', 'Indra', 'Joko', 'Kartika', 'Lina', 'Made', 'Nana', 'Omar', 'Putri', 'Qori', 'Rina', 'Sari', 'Tono', 'Umar', 'Vina', 'Wati', 'Yanto', 'Zaki'];
        $lastNames = ['Santoso', 'Wijaya', 'Kusuma', 'Pratama', 'Saputra', 'Lestari', 'Permana', 'Setiawan', 'Hartono', 'Gunawan', 'Susanto', 'Hidayat', 'Rahman', 'Nugroho', 'Wibowo'];
        $businesses = ['Tani', 'Petani', 'Kebun', 'Sawah', 'Hortikultura', 'Agro', 'Farm', 'Plantation'];
        $wilayahs = ['Malang Kota', 'Lawang', 'Batu', 'Singosari', 'Kepanjen', 'Tumpang', 'Pujon', 'Ngantang', 'Dampit', 'Turen', 'Gondanglegi', 'Pakis', 'Jabung', 'Wagir', 'Dau'];

        $customers = [];
        
        // Generate 1500 individual customers
        for ($i = 1; $i <= 1500; $i++) {
            $prefix = $prefixes[array_rand($prefixes)];
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            
            $customers[] = [
                'id_toko' => $tokoPusat->id_toko,
                'kode_pelanggan' => 'PLG-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'nama_pelanggan' => $prefix . ' ' . $firstName . ' ' . $lastName,
                'wilayah' => $wilayahs[array_rand($wilayahs)],
                'no_hp' => '08' . rand(1, 9) . rand(100000000, 999999999),
                'alamat' => 'Jl. ' . $lastNames[array_rand($lastNames)] . ' No. ' . rand(1, 200) . ', RT ' . rand(1, 10) . ' RW ' . rand(1, 10),
                'limit_piutang' => rand(1, 20) * 500000, // 500k - 10jt
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            // Insert in chunks of 500
            if (count($customers) >= 500) {
                DB::table('pelanggan')->insert($customers);
                $customers = [];
            }
        }
        
        // Generate 500 business customers (Kelompok Tani, Koperasi, etc)
        for ($i = 1501; $i <= 2000; $i++) {
            $business = $businesses[array_rand($businesses)];
            $location = $wilayahs[array_rand($wilayahs)];
            
            $customers[] = [
                'id_toko' => $tokoPusat->id_toko,
                'kode_pelanggan' => 'PLG-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'nama_pelanggan' => 'Kelompok ' . $business . ' ' . $location . ' ' . rand(1, 99),
                'wilayah' => $location,
                'no_hp' => '08' . rand(1, 9) . rand(100000000, 999999999),
                'alamat' => 'Jl. Raya ' . $location . ' No. ' . rand(1, 500),
                'limit_piutang' => rand(10, 50) * 1000000, // 10jt - 50jt for businesses
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            if (count($customers) >= 500) {
                DB::table('pelanggan')->insert($customers);
                $customers = [];
            }
        }
        
        // Insert remaining
        if (count($customers) > 0) {
            DB::table('pelanggan')->insert($customers);
        }
        
        echo "Created 2000 customers\n";
    }
}
