<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Satuan;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        $pupuk = Kategori::where('nama_kategori', 'Pupuk')->first();
        $pestisida = Kategori::where('nama_kategori', 'Pestisida')->first();
        $bibit = Kategori::where('nama_kategori', 'Bibit')->first();
        $alat = Kategori::where('nama_kategori', 'Alat Pertanian')->first();
        
        $kg = Satuan::where('nama_satuan', 'Kg')->first();
        $liter = Satuan::where('nama_satuan', 'Liter')->first();
        $pcs = Satuan::where('nama_satuan', 'Pcs')->first();
        $karung = Satuan::where('nama_satuan', 'Karung')->first();
        $botol = Satuan::where('nama_satuan', 'Botol')->first();

        $products = [
            [
                'sku' => 'PUP-001',
                'barcode' => '8991234567890',
                'nama_produk' => 'Pupuk Urea 50kg',
                'id_kategori' => $pupuk->id_kategori,
                'id_satuan_kecil' => $kg->id_satuan,
                'id_satuan_besar' => $karung->id_satuan,
                'nilai_konversi' => 50,
                'harga_beli' => 150000,
                'harga_jual_umum' => 180000,
            ],
            [
                'sku' => 'PUP-002',
                'barcode' => '8991234567891',
                'nama_produk' => 'Pupuk NPK Phonska 50kg',
                'id_kategori' => $pupuk->id_kategori,
                'id_satuan_kecil' => $kg->id_satuan,
                'id_satuan_besar' => $karung->id_satuan,
                'nilai_konversi' => 50,
                'harga_beli' => 180000,
                'harga_jual_umum' => 220000,
            ],
            [
                'sku' => 'PES-001',
                'barcode' => '8991234567892',
                'nama_produk' => 'Pestisida Decis 100ml',
                'id_kategori' => $pestisida->id_kategori,
                'id_satuan_kecil' => $botol->id_satuan,
                'id_satuan_besar' => $botol->id_satuan,
                'nilai_konversi' => 1,
                'harga_beli' => 45000,
                'harga_jual_umum' => 55000,
            ],
            [
                'sku' => 'PES-002',
                'barcode' => '8991234567893',
                'nama_produk' => 'Roundup 1 Liter',
                'id_kategori' => $pestisida->id_kategori,
                'id_satuan_kecil' => $liter->id_satuan,
                'id_satuan_besar' => $liter->id_satuan,
                'nilai_konversi' => 1,
                'harga_beli' => 85000,
                'harga_jual_umum' => 105000,
            ],
            [
                'sku' => 'BIB-001',
                'barcode' => '8991234567894',
                'nama_produk' => 'Bibit Padi Hibrida',
                'id_kategori' => $bibit->id_kategori,
                'id_satuan_kecil' => $kg->id_satuan,
                'id_satuan_besar' => $kg->id_satuan,
                'nilai_konversi' => 1,
                'harga_beli' => 35000,
                'harga_jual_umum' => 45000,
            ],
            [
                'sku' => 'BIB-002',
                'barcode' => '8991234567895',
                'nama_produk' => 'Bibit Jagung Hibrida',
                'id_kategori' => $bibit->id_kategori,
                'id_satuan_kecil' => $kg->id_satuan,
                'id_satuan_besar' => $kg->id_satuan,
                'nilai_konversi' => 1,
                'harga_beli' => 40000,
                'harga_jual_umum' => 50000,
            ],
            [
                'sku' => 'ALT-001',
                'barcode' => '8991234567896',
                'nama_produk' => 'Cangkul',
                'id_kategori' => $alat->id_kategori,
                'id_satuan_kecil' => $pcs->id_satuan,
                'id_satuan_besar' => $pcs->id_satuan,
                'nilai_konversi' => 1,
                'harga_beli' => 75000,
                'harga_jual_umum' => 95000,
            ],
            [
                'sku' => 'ALT-002',
                'barcode' => '8991234567897',
                'nama_produk' => 'Sprayer 16 Liter',
                'id_kategori' => $alat->id_kategori,
                'id_satuan_kecil' => $pcs->id_satuan,
                'id_satuan_besar' => $pcs->id_satuan,
                'nilai_konversi' => 1,
                'harga_beli' => 250000,
                'harga_jual_umum' => 320000,
            ],
            [
                'sku' => 'PUP-003',
                'barcode' => '8991234567898',
                'nama_produk' => 'Pupuk Organik Kompos 25kg',
                'id_kategori' => $pupuk->id_kategori,
                'id_satuan_kecil' => $kg->id_satuan,
                'id_satuan_besar' => $karung->id_satuan,
                'nilai_konversi' => 25,
                'harga_beli' => 50000,
                'harga_jual_umum' => 65000,
            ],
            [
                'sku' => 'PES-003',
                'barcode' => '8991234567899',
                'nama_produk' => 'Fungisida Antracol 500gr',
                'id_kategori' => $pestisida->id_kategori,
                'id_satuan_kecil' => $kg->id_satuan,
                'id_satuan_besar' => $kg->id_satuan,
                'nilai_konversi' => 1,
                'harga_beli' => 55000,
                'harga_jual_umum' => 70000,
            ],
        ];

        foreach ($products as $product) {
            Produk::create(array_merge($product, [
                'is_active' => true,
            ]));
        }
    }
}
