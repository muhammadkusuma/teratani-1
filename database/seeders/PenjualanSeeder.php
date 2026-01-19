<?php

namespace Database\Seeders;

use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use App\Models\RiwayatStok;
use App\Models\StokToko;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanSeeder extends Seeder
{
    public function run(): void
    {
        $tokos = Toko::all();
        $user = User::first();
        $pelanggans = Pelanggan::all();
        
        if ($tokos->isEmpty()) return;

        DB::beginTransaction();
        try {
            foreach ($tokos as $toko) {
                // Get random products for this shop
                $produks = Produk::with('satuanKecil')->inRandomOrder()->limit(10)->get();
                
                if ($produks->isEmpty()) {
                    continue;
                }

                // Create 5-10 random sales per shop
                $salesCount = rand(5, 10);
                
                for ($i = 0; $i < $salesCount; $i++) {
                    $date = now()->subDays(rand(1, 60));
                    $noFaktur = 'INV-' . $date->format('Ymd') . '-' . rand(1000, 9999);
                    
                    // Create Parent Penjualan
                    $penjualan = Penjualan::create([
                        'id_toko' => $toko->id_toko,
                        'id_user' => $user->id_user ?? 1,
                        'id_pelanggan' => $pelanggans->count() > 0 ? $pelanggans->random()->id_pelanggan : null,
                        'no_faktur' => $noFaktur,
                        'tgl_transaksi' => $date,
                        'tgl_jatuh_tempo' => $date->copy()->addDays(7),
                        'metode_bayar' => ['Tunai', 'Transfer', 'QRIS'][rand(0, 2)],
                        'status_transaksi' => 'Selesai',
                        'status_bayar' => 'Lunas',
                        'catatan' => 'Transaksi dummy seeding',
                        'total_bruto' => 0,
                        'diskon_nota' => 0,
                        'pajak_ppn' => 0,
                        'biaya_lain' => 0,
                        'total_netto' => 0,
                        'jumlah_bayar' => 0,
                        'kembalian' => 0,
                    ]);

                    // Create 1-5 details
                    $itemsCount = rand(1, 5);
                    $totalBruto = 0;
                    
                    for ($j = 0; $j < $itemsCount; $j++) {
                        $produk = $produks->random();
                        $qty = rand(1, 5);
                        $harga = $produk->harga_jual_umum ?? $produk->harga_beli * 1.2;
                        $subtotal = $qty * $harga;
                        
                        PenjualanDetail::create([
                            'id_penjualan' => $penjualan->id_penjualan,
                            'id_produk' => $produk->id_produk,
                            'qty' => $qty,
                            'satuan_jual' => $produk->satuanKecil->nama_satuan ?? 'Pcs',
                            'harga_modal_saat_jual' => $produk->harga_beli,
                            'harga_jual_satuan' => $harga,
                            'diskon_item' => 0,
                            'subtotal' => $subtotal,
                        ]);
                        
                        $totalBruto += $subtotal;

                        // Deduct Stock & Log
                        $stokToko = StokToko::firstOrCreate(
                            ['id_toko' => $toko->id_toko, 'id_produk' => $produk->id_produk],
                            ['stok_fisik' => 0, 'stok_minimal' => 5]
                        );
                        $stokToko->decrement('stok_fisik', $qty); // Allow negative for seeding if stock wasn't enough
                        
                        RiwayatStok::create([
                            'id_produk' => $produk->id_produk,
                            'id_toko' => $toko->id_toko,
                            'jenis' => 'keluar',
                            'jumlah' => $qty,
                            'stok_akhir' => $stokToko->stok_fisik,
                            'keterangan' => 'Penjualan Seeder',
                            'referensi' => $noFaktur,
                            'tanggal' => $date,
                        ]);
                    }

                    // Update totals
                    $netto = $totalBruto;
                    
                    $penjualan->update([
                        'total_bruto' => $totalBruto,
                        'total_netto' => $netto,
                        'jumlah_bayar' => $netto,
                    ]);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // throw $e; 
            echo "Error seeding penjualan: " . $e->getMessage() . "\n";
        }
    }
}
