<?php
namespace Database\Seeders;
use App\Models\PendapatanPasif;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Database\Seeder;

class PendapatanPasifSeeder extends Seeder
{
    public function run(): void
    {
        $tokos = Toko::all();
        $user = User::first();
        
        foreach ($tokos as $toko) {
            $count = rand(3, 7);
            for ($i = 1; $i <= $count; $i++) {
                $pendapatanNumber = PendapatanPasif::count() + 1;
                $date = now()->subDays(rand(1, 60));
                $today = $date->format('Ymd');
                
                PendapatanPasif::create([
                    'id_toko' => $toko->id_toko,
                    'id_user' => $user->id_user,
                    'kode_pendapatan' => 'INC-' . $today . '-' . str_pad($pendapatanNumber, 3, '0', STR_PAD_LEFT),
                    'tanggal_pendapatan' => $date,
                    'kategori' => ['Bunga Bank', 'Sewa Aset', 'Komisi', 'Investasi', 'Lainnya'][rand(0, 4)],
                    'sumber' => $this->getSumber($pendapatanNumber),
                    'jumlah' => rand(500, 10000) * 1000,
                    'metode_terima' => ['Tunai', 'Transfer'][rand(0, 1)],
                    'keterangan' => 'Pendapatan pasif dari ' . $this->getSumber($pendapatanNumber),
                ]);
            }
        }
    }
    
    private function getSumber($number) {
        $sumber = [
            'Bunga deposito Bank Mandiri',
            'Sewa gedung lantai 2',
            'Komisi penjualan afiliasi',
            'Return investasi reksadana',
            'Pendapatan iklan online',
            'Sewa kendaraan operasional',
            'Bunga tabungan',
            'Dividen saham',
        ];
        return $sumber[($number - 1) % count($sumber)];
    }
}
