<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UtangPiutangPelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pelanggans = \App\Models\Pelanggan::all();
        
        if ($pelanggans->isEmpty()) {
            $this->command->warn('No pelanggan found. Skipping UtangPiutangPelangganSeeder.');
            return;
        }

        // Sample transactions for first 3 customers
        foreach ($pelanggans->take(3) as $index => $pelanggan) {
            $saldo = 0;
            
            // Transaction 1: Piutang baru (penjualan kredit)
            $saldo += 1500000;
            \App\Models\UtangPiutangPelanggan::create([
                'id_pelanggan'   => $pelanggan->id_pelanggan,
                'tanggal'        => now()->subDays(25),
                'jenis_transaksi' => 'piutang',
                'nominal'        => 1500000,
                'keterangan'     => 'Penjualan kredit - INV#001',
                'no_referensi'   => 'INV-001',
                'saldo_piutang'  => $saldo,
            ]);

            // Transaction 2: Pembayaran sebagian
            $saldo -= 500000;
            \App\Models\UtangPiutangPelanggan::create([
                'id_pelanggan'   => $pelanggan->id_pelanggan,
                'tanggal'        => now()->subDays(15),
                'jenis_transaksi' => 'pembayaran',
                'nominal'        => 500000,
                'keterangan'     => 'Pembayaran pertama',
                'no_referensi'   => 'BAYAR-001',
                'saldo_piutang'  => $saldo,
            ]);

            // Transaction 3: Piutang baru
            $saldo += 2000000;
            \App\Models\UtangPiutangPelanggan::create([
                'id_pelanggan'   => $pelanggan->id_pelanggan,
                'tanggal'        => now()->subDays(7),
                'jenis_transaksi' => 'piutang',
                'nominal'        => 2000000,
                'keterangan'     => 'Penjualan kredit - INV#002',
                'no_referensi'   => 'INV-002',
                'saldo_piutang'  => $saldo,
            ]);

            // Transaction 4: Pelunasan
            $saldo -= 1000000;
            \App\Models\UtangPiutangPelanggan::create([
                'id_pelanggan'   => $pelanggan->id_pelanggan,
                'tanggal'        => now()->subDays(2),
                'jenis_transaksi' => 'pembayaran',
                'nominal'        => 1000000,
                'keterangan'     => 'Pembayaran kedua',
                'no_referensi'   => 'BAYAR-002',
                'saldo_piutang'  => $saldo,
            ]);
        }

        $this->command->info('Sample utang piutang pelanggan created successfully.');
    }
}
