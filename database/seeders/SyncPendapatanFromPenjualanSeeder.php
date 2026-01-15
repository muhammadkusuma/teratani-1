<?php
namespace Database\Seeders;

use App\Models\Penjualan;
use App\Models\PendapatanPasif;
use Illuminate\Database\Seeder;

class SyncPendapatanFromPenjualanSeeder extends Seeder
{
    public function run(): void
    {
        // Get all penjualan that don't have pendapatan entry yet
        $penjualans = Penjualan::whereDoesntHave('pendapatanPasif')->get();
        
        foreach ($penjualans as $penjualan) {
            PendapatanPasif::create([
                'id_toko' => $penjualan->id_toko,
                'id_user' => $penjualan->id_user,
                'id_penjualan' => $penjualan->id_penjualan,
                'kode_pendapatan' => 'INC-SLS-' . $penjualan->tgl_transaksi->format('Ymd') . '-' . str_pad($penjualan->id_penjualan, 3, '0', STR_PAD_LEFT),
                'tanggal_pendapatan' => $penjualan->tgl_transaksi,
                'kategori' => 'Penjualan',
                'sumber' => 'Penjualan #' . $penjualan->no_faktur,
                'jumlah' => $penjualan->total_netto,
                'metode_terima' => $penjualan->metode_bayar == 'Tunai' ? 'Tunai' : 'Transfer',
                'is_otomatis' => true,
                'keterangan' => 'Pendapatan otomatis dari transaksi penjualan',
            ]);
        }
        
        $this->command->info('Synced ' . $penjualans->count() . ' penjualan to pendapatan.');
    }
}
