<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Satuan;
use Illuminate\Support\Facades\DB;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = Kategori::all();
        $satuans = Satuan::all();
        
        $kg = $satuans->where('nama_satuan', 'Kg')->first();
        $liter = $satuans->where('nama_satuan', 'Liter')->first();
        $pcs = $satuans->where('nama_satuan', 'Pcs')->first();
        $karung = $satuans->where('nama_satuan', 'Karung')->first();
        $botol = $satuans->where('nama_satuan', 'Botol')->first();

        // Base products
        $baseProducts = [
            ['prefix' => 'PUP', 'kategori' => 'Pupuk', 'names' => ['Urea', 'NPK Phonska', 'Organik Kompos', 'TSP', 'KCL', 'ZA', 'Petroganik', 'NPK Mutiara', 'Pupuk Kandang', 'Pupuk Hijau']],
            ['prefix' => 'PES', 'kategori' => 'Pestisida', 'names' => ['Decis', 'Roundup', 'Antracol', 'Regent', 'Demolish', 'Virtako', 'Marshal', 'Curacron', 'Gramoxone', 'Buldok']],
            ['prefix' => 'BIB', 'kategori' => 'Bibit', 'names' => ['Padi Hibrida', 'Jagung Hibrida', 'Cabai Merah', 'Tomat', 'Terong', 'Kangkung', 'Bayam', 'Sawi', 'Kacang Panjang', 'Mentimun']],
            ['prefix' => 'ALT', 'kategori' => 'Alat Pertanian', 'names' => ['Cangkul', 'Sprayer 16L', 'Sabit', 'Garpu Tanah', 'Gembor', 'Selang Air', 'Pompa Air', 'Gunting Tanaman', 'Sekop', 'Parang']],
        ];

        $products = [];
        $counter = 1;

        foreach ($baseProducts as $category) {
            $kategori = $kategoris->where('nama_kategori', $category['kategori'])->first();
            
            foreach ($category['names'] as $index => $name) {
                // Create 100 variants per product (different sizes, brands, etc)
                for ($variant = 1; $variant <= 100; $variant++) {
                    $sku = sprintf('%s-%04d-%03d', $category['prefix'], $index + 1, $variant);
                    $barcode = '899' . str_pad($counter, 10, '0', STR_PAD_LEFT);
                    
                    // Randomize sizes and prices
                    $sizes = ['500gr', '1kg', '5kg', '10kg', '25kg', '50kg', '100ml', '250ml', '500ml', '1L'];
                    $size = $sizes[array_rand($sizes)];
                    
                    $hargaBeli = rand(5000, 500000);
                    $hargaJual = $hargaBeli * (1 + (rand(10, 40) / 100)); // 10-40% markup
                    
                    $products[] = [
                        'sku' => $sku,
                        'barcode' => $barcode,
                        'nama_produk' => $name . ' ' . $size . ' Var-' . $variant,
                        'id_kategori' => $kategori->id_kategori,
                        'id_satuan_kecil' => $category['kategori'] === 'Pestisida' ? $botol->id_satuan : ($category['kategori'] === 'Alat Pertanian' ? $pcs->id_satuan : $kg->id_satuan),
                        'id_satuan_besar' => $category['kategori'] === 'Alat Pertanian' ? $pcs->id_satuan : $karung->id_satuan,
                        'nilai_konversi' => $category['kategori'] === 'Alat Pertanian' ? 1 : rand(1, 50),
                        'harga_beli' => $hargaBeli,
                        'harga_jual_umum' => $hargaJual,
                        'harga_jual_grosir' => $hargaJual * 0.95, // 5% discount for wholesale
                        'gambar_produk' => null,
                        'is_active' => rand(0, 10) > 1, // 90% active
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    
                    $counter++;
                    
                    // Insert in chunks of 500 to avoid memory issues
                    if (count($products) >= 500) {
                        DB::table('produk')->insert($products);
                        $products = [];
                    }
                }
            }
        }
        
        // Insert remaining products
        if (count($products) > 0) {
            DB::table('produk')->insert($products);
        }
        
        echo "Created " . ($counter - 1) . " products\n";
    }
}
