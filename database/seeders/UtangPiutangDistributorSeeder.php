<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UtangPiutangDistributorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $distributors = \App\Models\Distributor::all();
        
        if ($distributors->isEmpty()) {
            $this->command->warn('No distributors found. Skipping UtangPiutangDistributorSeeder.');
            return;
        }

        // Sample transactions for first 2 distributors
        foreach ($distributors->take(2) as $index => $distributor) {
            $saldo = 0;
            
            // Transaction 1: Utang baru
            $saldo += 5000000;
            \App\Models\UtangPiutangDistributor::create([
                'id_distributor' => $distributor->id_distributor,
                'tanggal'        => now()->subDays(30),
                'jenis_transaksi' => 'utang',
                'nominal'        => 5000000,
                'keterangan'     => 'Pembelian barang - PO#001',
                'no_referensi'   => 'PO-001',
                'saldo_utang'    => $saldo,
            ]);

            // Transaction 2: Pembayaran sebagian
            $saldo -= 2000000;
            \App\Models\UtangPiutangDistributor::create([
                'id_distributor' => $distributor->id_distributor,
                'tanggal'        => now()->subDays(20),
                'jenis_transaksi' => 'pembayaran',
                'nominal'        => 2000000,
                'keterangan'     => 'Pembayaran cicilan pertama',
                'no_referensi'   => 'BYR-001',
                'saldo_utang'    => $saldo,
            ]);

            // Transaction 3: Utang baru lagi
            $saldo += 3500000;
            \App\Models\UtangPiutangDistributor::create([
                'id_distributor' => $distributor->id_distributor,
                'tanggal' => now()->subDays(10),
                'jenis_transaksi' => 'utang',
                'nominal'        => 3500000,
                'keterangan'     => 'Pembelian barang - PO#002',
                'no_referensi'   => 'PO-002',
                'saldo_utang'    => $saldo,
            ]);

            // Transaction 4: Pembayaran
            $saldo -= 1500000;
            \App\Models\UtangPiutangDistributor::create([
                'id_distributor' => $distributor->id_distributor,
                'tanggal'        => now()->subDays(5),
                'jenis_transaksi' => 'pembayaran',
                'nominal'        => 1500000,
                'keterangan'     => 'Pembayaran cicilan kedua',
                'no_referensi'   => 'BYR-002',
                'saldo_utang'    => $saldo,
            ]);
        }

        $this->command->info('Sample utang piutang distributor created successfully.');
    }
}
