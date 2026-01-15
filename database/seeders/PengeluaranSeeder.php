<?php
namespace Database\Seeders;
use App\Models\Pengeluaran;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Database\Seeder;

class PengeluaranSeeder extends Seeder
{
    public function run(): void
    {
        $tokos = Toko::all();
        $user = User::first();
        
        foreach ($tokos as $toko) {
            $count = rand(5, 10);
            for ($i = 1; $i <= $count; $i++) {
                $pengeluaranNumber = Pengeluaran::count() + 1;
                $date = now()->subDays(rand(1, 60));
                $today = $date->format('Ymd');
                
                Pengeluaran::create([
                    'id_toko' => $toko->id_toko,
                    'id_user' => $user->id_user,
                    'kode_pengeluaran' => 'BYA-' . $today . '-' . str_pad($pengeluaranNumber, 3, '0', STR_PAD_LEFT),
                    'tanggal_pengeluaran' => $date,
                    'kategori' => ['Gaji', 'Listrik', 'Air', 'Sewa', 'ATK', 'Transportasi', 'Pemeliharaan', 'Pajak', 'Lainnya'][rand(0, 8)],
                    'deskripsi' => $this->getDeskripsi($pengeluaranNumber),
                    'jumlah' => rand(100, 5000) * 1000,
                    'metode_bayar' => ['Tunai', 'Transfer', 'Kredit'][rand(0, 2)],
                    'keterangan' => 'Pengeluaran operasional toko',
                ]);
            }
        }
    }
    
    private function getDeskripsi($number) {
        $deskripsi = [
            'Pembayaran gaji karyawan bulan ini',
            'Tagihan listrik PLN',
            'Tagihan air PDAM',
            'Sewa tempat usaha',
            'Pembelian alat tulis kantor',
            'Biaya transportasi pengiriman',
            'Pemeliharaan dan perbaikan',
            'Pembayaran pajak usaha',
            'Biaya operasional lainnya',
        ];
        return $deskripsi[($number - 1) % count($deskripsi)];
    }
}
