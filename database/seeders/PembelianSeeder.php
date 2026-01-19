<?php

namespace Database\Seeders;

use App\Models\Distributor;
use App\Models\Gudang;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Produk;
use App\Models\RiwayatStok;
use App\Models\StokGudang;
use App\Models\Toko;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PembelianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tokos = Toko::all();
        $gudangs = Gudang::all();
        $distributors = Distributor::all();
        $produks = Produk::where('is_active', 1)->inRandomOrder()->limit(20)->get();

        if ($distributors->isEmpty() || $produks->isEmpty()) {
            return;
        }

        DB::beginTransaction();
        try {
            // Create 20 dummy purchases
            for ($i = 0; $i < 20; $i++) {
                $distributor = $distributors->random();
                $toko = $tokos->random();
                
                // Randomly decide specific destination (Toko or Gudang)
                $isGudang = rand(0, 1) == 1 && $gudangs->isNotEmpty();
                $targetId = $isGudang ? $gudangs->random()->id_gudang : $toko->id_toko;
                $targetType = $isGudang ? 'gudang' : 'toko';

                $tanggal = now()->subDays(rand(1, 90));
                
                $pembelian = Pembelian::create([
                    'id_distributor' => $distributor->id_distributor,
                    'no_faktur' => 'INV-DIST-' . rand(10000, 99999),
                    'tanggal' => $tanggal,
                    'total' => 0, // Update later
                    'keterangan' => 'Seeding Pembelian Dummy',
                ]);

                $totalPembelian = 0;
                $itemCount = rand(2, 5);
                
                for ($j = 0; $j < $itemCount; $j++) {
                    $produk = $produks->random();
                    $qty = rand(10, 100);
                    $hargaSatuan = $produk->harga_beli;
                    $totalHarga = $qty * $hargaSatuan;

                    PembelianDetail::create([
                        'id_pembelian' => $pembelian->id_pembelian,
                        'id_produk' => $produk->id_produk,
                        'jumlah' => $qty,
                        'harga_satuan' => $hargaSatuan,
                        'total_harga' => $totalHarga,
                    ]);

                    $totalPembelian += $totalHarga;

                    // Update Stock & Log History to match system design
                    $stokAkhir = 0;
                    if ($targetType === 'gudang') {
                        $stok = StokGudang::firstOrCreate(
                            ['id_gudang' => $targetId, 'id_produk' => $produk->id_produk],
                            ['stok_fisik' => 0]
                        );
                        $stok->increment('stok_fisik', $qty);
                        $stokAkhir = $stok->stok_fisik;
                        
                        RiwayatStok::create([
                            'id_produk' => $produk->id_produk,
                            'id_gudang' => $targetId,
                            'jenis' => 'masuk',
                            'jumlah' => $qty,
                            'stok_akhir' => $stokAkhir,
                            'keterangan' => 'Pembelian Seeder',
                            'referensi' => 'PEMBELIAN-' . $pembelian->id_pembelian,
                            'tanggal' => $tanggal,
                        ]);
                    } else {
                        // Toko
                        // Note: StokToko might already exist from StokTokoSeeder, or not.
                        // We strictly use firstOrCreate here.
                         $stok = \App\Models\StokToko::firstOrCreate(
                            ['id_toko' => $targetId, 'id_produk' => $produk->id_produk],
                            ['stok_fisik' => 0, 'stok_minimal' => 5]
                        );
                        $stok->increment('stok_fisik', $qty);
                        $stokAkhir = $stok->stok_fisik;
                        
                        RiwayatStok::create([
                            'id_produk' => $produk->id_produk,
                            'id_toko' => $targetId,
                            'jenis' => 'masuk',
                            'jumlah' => $qty,
                            'stok_akhir' => $stokAkhir,
                            'keterangan' => 'Pembelian Seeder',
                            'referensi' => 'PEMBELIAN-' . $pembelian->id_pembelian,
                            'tanggal' => $tanggal,
                        ]);
                    }
                }

                $pembelian->update(['total' => $totalPembelian]);
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // In seeder we might just echo error
            echo "Error seeding pembelian: " . $e->getMessage() . "\n";
        }
    }
}
