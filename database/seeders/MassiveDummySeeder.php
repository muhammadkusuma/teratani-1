<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Toko;
use App\Models\User;
use App\Models\Kategori;
use App\Models\Satuan;

class MassiveDummySeeder extends Seeder
{
    public function run()
    {
        // 1. Setup Dependencies
        $toko = Toko::first();
        if (!$toko) {
            $this->command->error("Harap buat data Toko terlebih dahulu.");
            return;
        }
        $id_toko = $toko->id_toko;

        $user = User::first();
        $id_user = $user->id;

        $kategoris = Kategori::pluck('id_kategori')->toArray();
        $satuans = Satuan::where('tipe', 'kecil')->pluck('id_satuan')->toArray();

        if (empty($kategoris) || empty($satuans)) {
            $this->command->error("Harap seed Kategori & Satuan terlebih dahulu.");
            return;
        }

        $this->command->info("STARTING MASSIVE SEEDING V3.1 (FIXED VISIBILITY)...");
        $timestamp = Carbon::now();

        // 0. Clean old dummy data
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        $tables = [
            'penjualan_detail', 'penjualan', 'stok_toko', 'produk', 'pelanggan',
            'retur_penjualan_detail', 'retur_penjualan', 'pengeluaran', 
            'pendapatan_pasif', 'utang_piutang_pelanggan', 'riwayat_stok',
            'stok_gudang', 'gudang',
            // NEW TABLES
            'utang_piutang_distributor', 'retur_pembelian_detail', 'retur_pembelian', 
            'pembelian_detail', 'pembelian', 'distributor'
        ];
        foreach($tables as $tbl) {
            DB::table($tbl)->truncate();
        }
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
        $this->command->info("Old data cleared.");

        // ----------------------------------------------------
        // 1. WAREHOUSES
        // ----------------------------------------------------
        $this->command->info("Generating Warehouses...");
        $gudangIds = [];
        for($i=1; $i<=5; $i++) {
            $gid = DB::table('gudang')->insertGetId([
                'nama_gudang' => "Gudang Dummy #$i",
                'lokasi' => "Lokasi Gudang $i",
                'id_toko' => $id_toko,
                'created_at' => $timestamp,
                'updated_at' => $timestamp
            ]);
            $gudangIds[] = $gid;
        }

        // ----------------------------------------------------
        // 2. DISTRIBUTORS
        // ----------------------------------------------------
        $this->command->info("Generating 50 Distributors...");
        $distributorIds = [];
        for($i=1; $i<=50; $i++) {
            $did = DB::table('distributor')->insertGetId([
                'id_toko' => $id_toko,
                'kode_distributor' => "SUP-" . str_pad($i, 4, '0', STR_PAD_LEFT),
                'nama_distributor' => "Distributor Dummy $i",
                'nama_perusahaan' => "PT. Sumber Tani $i",
                'alamat' => "Jl. Raya Supplier No. $i",
                'kota' => "Jakarta",
                'no_telp' => "021-555" . str_pad($i, 4, '0', STR_PAD_LEFT),
                'nama_kontak' => "Sales Agent $i",
                'no_hp_kontak' => "0819" . rand(10000000, 99999999),
                'is_active' => 1,
                'created_at' => $timestamp,
                'updated_at' => $timestamp
            ]);
            $distributorIds[] = $did;
        }


        // ----------------------------------------------------
        // 3. PRODUCTS & STOCKS
        // ----------------------------------------------------
        $this->command->info("Generating 1,000 Products...");
        $products = [];
        $stockTokoInserts = [];
        $stockGudangInserts = [];
        $riwayatStokInserts = [];

        for ($i = 1; $i <= 1000; $i++) {
             $hargaBeli = rand(10, 500) * 100; 
             $hargaJual = ceil(($hargaBeli * rand(110, 150) / 100) / 100) * 100;
             $products[] = [
                'nama_produk' => "Produk Dummy #$i",
                'sku' => "SKU-" . str_pad($i, 6, '0', STR_PAD_LEFT),
                'barcode' => "899" . str_pad($i, 10, '0', STR_PAD_LEFT),
                'id_kategori' => $kategoris[array_rand($kategoris)],
                'id_satuan_kecil' => $satuans[array_rand($satuans)],
                'harga_beli' => $hargaBeli,
                'harga_jual_umum' => $hargaJual,
                'harga_jual_grosir' => $hargaJual * 0.95,
                'harga_r1' => $hargaJual * 0.98,
                'harga_r2' => $hargaJual * 0.97,
                'is_active' => 1,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ];
        }

        foreach (array_chunk($products, 500) as $chunk) {
            DB::table('produk')->insertOrIgnore($chunk);
        }

        $allProductIds = DB::table('produk')->where('sku', 'LIKE', 'SKU-%')->pluck('id_produk')->toArray();

        // ----------------------------------------------------
        // 4. PURCHASES (Pembelian) & DEBTS (Utang)
        // ----------------------------------------------------
        $this->command->info("Generating Purhcases (Including THIS MONTH)...");

        foreach($distributorIds as $did) {
            // Each distributor has ~10 purchases
            $purchaseCount = rand(5, 15);
            
            for($p=0; $p<$purchaseCount; $p++) {
                // Ensure some dates are RECENT (this month)
                if ($p < 2) {
                    $purDate = Carbon::now()->subDays(rand(0, 10)); // Recent
                } else {
                    $purDate = Carbon::create(2025, rand(1, 12), rand(1, 28)); // History
                }

                $faktur = "INV-" . $did . "-" . $p . rand(100,999);
                
                // 30% credit
                $isCredit = (rand(1, 10) <= 3);
                
                // Purchase Details
                $itemCount = rand(5, 20);
                $totalPurchase = 0;
                $purDetails = [];
                $localRiwayat = [];

                for($k=0; $k<$itemCount; $k++) {
                    $pid = $allProductIds[array_rand($allProductIds)];
                    $qty = rand(10, 100);
                    $price = rand(10, 500) * 100; // Simluating buy price
                    $subtotal = $price * $qty;
                    $totalPurchase += $subtotal;

                    $purDetails[] = [
                        'id_produk' => $pid,
                        'jumlah' => $qty,
                        'harga_satuan' => $price,
                        'total_harga' => $subtotal
                    ];

                    $localRiwayat[] = [
                        'id_produk' => $pid,
                        'id_toko' => $id_toko, // Assume mostly to store
                        'jenis' => 'masuk',
                        'jumlah' => $qty,
                        'stok_akhir' => 0, // Ignored
                        'keterangan' => 'Pembelian ' . $faktur,
                        'referensi' => $faktur,
                        'tanggal' => $purDate->format('Y-m-d'),
                        'created_at' => $purDate,
                        'updated_at' => $purDate,
                    ];
                }

                $purId = DB::table('pembelian')->insertGetId([
                    'id_distributor' => $did,
                    'no_faktur' => $faktur,
                    'tanggal' => $purDate->format('Y-m-d'),
                    'total' => $totalPurchase,
                    'keterangan' => 'Pembelian Dummy',
                    'created_at' => $purDate,
                    'updated_at' => $purDate
                ]);

                // Insert Details
                foreach($purDetails as $pd) {
                    $pd['id_pembelian'] = $purId;
                    $pd['created_at'] = $purDate;
                    $pd['updated_at'] = $purDate;
                    DB::table('pembelian_detail')->insert($pd);
                }

                // Insert Riwayat Stok
                foreach($localRiwayat as $lr) {
                     $riwayatStokInserts[] = $lr;
                }

                // Insert Debt if Credit
                if($isCredit) {
                    DB::table('utang_piutang_distributor')->insert([
                        'id_distributor' => $did,
                        'tanggal' => $purDate->format('Y-m-d'),
                        'jenis_transaksi' => 'utang',
                        'nominal' => $totalPurchase,
                        'keterangan' => 'Hutang Pembelian ' . $faktur,
                        'no_referensi' => $faktur,
                        'saldo_utang' => $totalPurchase, // Simplified
                        'created_at' => $purDate,
                        'updated_at' => $purDate
                    ]);
                }

                // Generate Return (Retur Pembelian) - 10% chance
                if(rand(1, 100) <= 10) {
                    $retTotal = 0;
                    $retDetails = [];
                    // Pick 1 item to return
                    $retItem = $purDetails[0];
                    $retQty = rand(1, 5);
                    $retPrice = $retItem['harga_satuan'];
                    $retSub = $retQty * $retPrice;
                    $retTotal = $retSub;
                    $retDate = $purDate->copy()->addDays(rand(1,5));
                    
                    // FIX: Must allow null gudang if returning from Toko, OR pick a random gudang.
                    // However, controller requires Valid Gudang ID for filtering.
                    // So we will pick a random gudang to make it show up in the filtered list.
                    $randomGudangId = $gudangIds[array_rand($gudangIds)];

                    $retId = DB::table('retur_pembelian')->insertGetId([
                        'id_pembelian' => $purId,
                        'id_distributor' => $did,
                        'id_gudang' => $randomGudangId, // FIXED: Now using a valid gudang ID
                        'tgl_retur' => $retDate->format('Y-m-d'),
                        'total_retur' => $retTotal,
                        'keterangan' => 'Retur Barang Rusak',
                        'created_at' => $retDate,
                        'updated_at' => $retDate
                    ]);

                    DB::table('retur_pembelian_detail')->insert([
                        'id_retur_pembelian' => $retId,
                        'id_produk' => $retItem['id_produk'],
                        'qty' => $retQty,
                        'harga_satuan' => $retPrice,
                        'subtotal' => $retSub,
                        'alasan' => 'Cacat Produksi',
                        'created_at' => $retDate,
                        'updated_at' => $retDate
                    ]);
                }
            }
        }
        $this->command->info("Purchases Generated!");


        // ----------------------------------------------------
        // Initial Stock (Add on top of purchases)
        // ----------------------------------------------------
        foreach ($allProductIds as $pid) {
            $stokToko = rand(50, 500);
            $stockTokoInserts[] = [
                'id_toko' => $id_toko,
                'id_produk' => $pid,
                'stok_fisik' => $stokToko,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ];

            if(rand(1, 10) > 8) {
                $gid = $gudangIds[array_rand($gudangIds)];
                $stokGudang = rand(100, 1000);
                $stockGudangInserts[] = [
                    'id_gudang' => $gid,
                    'id_produk' => $pid,
                    'stok_fisik' => $stokGudang,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ];
            }
        }
        foreach (array_chunk($stockTokoInserts, 500) as $chunk) DB::table('stok_toko')->insertOrIgnore($chunk);
        foreach (array_chunk($stockGudangInserts, 500) as $chunk) DB::table('stok_gudang')->insertOrIgnore($chunk);
        
        // Use chunks for Riwayat too
        foreach (array_chunk($riwayatStokInserts, 1000) as $chunk) DB::table('riwayat_stok')->insertOrIgnore($chunk);


        // ----------------------------------------------------
        // 5. CUSTOMERS
        // ----------------------------------------------------
        $this->command->info("Generating 100 Customers...");
        $customers = [];
        for ($i = 1; $i <= 100; $i++) {
            $customers[] = [
                'id_toko' => $id_toko,
                'kode_pelanggan' => "CUST-" . str_pad($i, 4, '0', STR_PAD_LEFT),
                'nama_pelanggan' => "Pelanggan Dummy $i",
                'no_hp' => "0812" . rand(10000000, 99999999),
                'alamat' => "Alamat Dummy No. $i",
                'kategori_harga' => ['umum', 'grosir', 'r1', 'r2'][array_rand(['umum', 'grosir', 'r1', 'r2'])],
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ];
        }
        DB::table('pelanggan')->insertOrIgnore($customers);
        $customerIds = DB::table('pelanggan')->where('kode_pelanggan', 'LIKE', 'CUST-%')->pluck('id_pelanggan')->toArray();


        // ----------------------------------------------------
        // 6. EXPENSES & PASSIVE INCOME
        // ----------------------------------------------------
        $this->command->info("Generating Expenses & Income...");
        // (Similar logic to V2, retained for completeness)
        $expenseCategories = ['Gaji', 'Listrik', 'Air', 'Sewa', 'ATK', 'Transportasi', 'Pemeliharaan', 'Lainnya'];
        $expenses = [];
        for ($i = 1; $i <= 300; $i++) {
            $date = Carbon::create(2025, rand(1, 12), rand(1, 28)); 
            $expenses[] = [
                'id_toko' => $id_toko,
                'id_user' => $id_user,
                'kode_pengeluaran' => "EXP-" . str_pad($i, 5, '0', STR_PAD_LEFT),
                'tanggal_pengeluaran' => $date->format('Y-m-d'),
                'kategori' => $expenseCategories[array_rand($expenseCategories)],
                'deskripsi' => "Pengeluaran Dummy $i",
                'jumlah' => rand(50, 5000) * 1000,
                'metode_bayar' => 'Tunai',
                'created_at' => $date,
                'updated_at' => $date,
            ];
        }
        foreach (array_chunk($expenses, 500) as $chunk) DB::table('pengeluaran')->insertOrIgnore($chunk);
        
        $incomeCategories = ['Bunga Bank', 'Sewa Aset', 'Komisi', 'Lainnya'];
        $incomes = [];
        for ($i = 1; $i <= 100; $i++) {
            $date = Carbon::create(2025, rand(1, 12), rand(1, 28));
            $incomes[] = [
                'id_toko' => $id_toko,
                'id_user' => $id_user,
                'kode_pendapatan' => "INC-" . str_pad($i, 5, '0', STR_PAD_LEFT),
                'tanggal_pendapatan' => $date->format('Y-m-d'),
                'kategori' => $incomeCategories[array_rand($incomeCategories)],
                'sumber' => "Sumber Dummy $i",
                'jumlah' => rand(100, 10000) * 1000,
                'created_at' => $date,
                'updated_at' => $date,
            ];
        }
        foreach (array_chunk($incomes, 500) as $chunk) DB::table('pendapatan_pasif')->insertOrIgnore($chunk);


        // ----------------------------------------------------
        // 7. SALES (Penjualan)
        // ----------------------------------------------------
        $this->command->info("Generating Sales Transactions (4,000/day for 25 days = 100k)...");
        
        $daysToSeed = 25;
        $dailyTransactions = 4000;
        $batchSize = 1000; 
        
        for ($d = 0; $d < $daysToSeed; $d++) {
            $currentDate = Carbon::today()->subDays($d);
            $formattedDate = $currentDate->format('Y-m-d');
            $this->command->info("Generating 4000 transactions for $formattedDate...");

            $batches = ceil($dailyTransactions / $batchSize);

            for ($b = 1; $b <= $batches; $b++) {
                $transData = [];
                $batchFakturs = [];
                $batchDetailsConfig = []; 
                $piutangData = [];

                for ($i = 0; $i < $batchSize; $i++) {
                    $isCredit = (rand(1, 100) <= 10);
                    $customerId = $isCredit ? $customerIds[array_rand($customerIds)] : 
                                 ((rand(0, 10) > 7) ? $customerIds[array_rand($customerIds)] : null);

                    $faktur = "TRX-S-" . $formattedDate . "-" . $b . "-" . $i . "-" . rand(100,999);
                    $batchFakturs[] = $faktur;

                    $itemCount = rand(1, 5); 
                    $thisResTotal = 0;
                    $thisResItems = [];

                    for ($k = 0; $k < $itemCount; $k++) {
                        $prodId = $allProductIds[array_rand($allProductIds)];
                        $dummyPrice = rand(10, 100) * 1000; 
                        $qty = rand(1, 5);
                        $subtotal = $dummyPrice * $qty;
                        $thisResTotal += $subtotal;
                        
                        $thisResItems[] = [
                            'id_produk' => $prodId,
                            'qty' => $qty,
                            'harga' => $dummyPrice,
                            'subtotal' => $subtotal
                        ];
                    }
                    
                    $batchDetailsConfig[$faktur] = $thisResItems; 

                    $transData[] = [
                        'id_toko' => $id_toko,
                        'id_user' => $id_user,
                        'id_pelanggan' => $customerId,
                        'no_faktur' => $faktur,
                        'tgl_transaksi' => $currentDate,
                        'status_bayar' => $isCredit ? 'Belum Lunas' : 'Lunas',
                        'metode_bayar' => $isCredit ? 'Hutang' : 'Tunai',
                        'total_bruto' => $thisResTotal,
                        'total_netto' => $thisResTotal,
                        'jumlah_bayar' => $isCredit ? 0 : $thisResTotal,
                        'kembalian' => 0,
                        'created_at' => $currentDate,
                        'updated_at' => $currentDate,
                    ];

                    if ($isCredit && $customerId) {
                        $piutangData[] = [
                            'pelanggan_id' => $customerId,
                            'tgl' => $formattedDate,
                            'nominal' => $thisResTotal,
                            'no_ref' => $faktur,
                            'created_at' => $currentDate
                        ];
                    }
                }

                DB::table('penjualan')->insertOrIgnore($transData);

                $insertedTrans = DB::table('penjualan')
                                    ->whereIn('no_faktur', $batchFakturs)
                                    ->select('id_penjualan', 'no_faktur', 'id_pelanggan', 'id_toko', 'tgl_transaksi', 'id_user')
                                    ->get();
                $transMap = $insertedTrans->keyBy('no_faktur');

                $detailData = [];
                $dbSalesRiwayatInserts = [];
                $returnCandidates = []; 

                foreach ($batchDetailsConfig as $fkt => $items) {
                    if (!$transMap->has($fkt)) continue;
                    $trans = $transMap[$fkt];
                    $tid = $trans->id_penjualan;

                    foreach ($items as $item) {
                         $detailData[] = [
                            'id_penjualan' => $tid,
                            'id_produk' => $item['id_produk'],
                            'qty' => $item['qty'],
                            'satuan_jual' => 'Pcs',
                            'harga_modal_saat_jual' => $item['harga'] * 0.8,
                            'harga_jual_satuan' => $item['harga'],
                            'diskon_item' => 0,
                            'subtotal' => $item['subtotal'],
                        ];

                        $dbSalesRiwayatInserts[] = [
                            'id_produk' => $item['id_produk'],
                            'id_toko' => $id_toko,
                            'jenis' => 'keluar',
                            'jumlah' => $item['qty'],
                            'stok_akhir' => 0,
                            'keterangan' => 'Penjualan ' . $fkt,
                            'referensi' => $fkt,
                            'tanggal' => $trans->tgl_transaksi,
                            'created_at' => $trans->tgl_transaksi,
                            'updated_at' => $trans->tgl_transaksi,
                        ];
                    }

                    if (rand(1, 100) <= 3 && $trans->id_pelanggan) {
                        $returnCandidates[] = [
                            'trans' => $trans,
                            'items' => $items
                        ];
                    }
                }

                foreach(array_chunk($detailData, 1000) as $chunk) DB::table('penjualan_detail')->insertOrIgnore($chunk);
                foreach(array_chunk($dbSalesRiwayatInserts, 1000) as $chunk) DB::table('riwayat_stok')->insertOrIgnore($chunk);

                $realPiutangInserts = [];
                foreach($piutangData as $pd) {
                    $realPiutangInserts[] = [
                        'id_pelanggan' => $pd['pelanggan_id'],
                        'tanggal' => $pd['tgl'],
                        'jenis_transaksi' => 'piutang',
                        'nominal' => $pd['nominal'],
                        'keterangan' => 'Penjualan Kredit',
                        'no_referensi' => $pd['no_ref'],
                        'saldo_piutang' => $pd['nominal'],
                        'created_at' => $pd['created_at'],
                        'updated_at' => $pd['created_at'],
                    ];
                }
                if(!empty($realPiutangInserts)) DB::table('utang_piutang_pelanggan')->insertOrIgnore($realPiutangInserts);

                // Generate Returns
                $returDetailData = [];
                foreach ($returnCandidates as $rc) {
                    $trans = $rc['trans'];
                    $items = $rc['items'];
                    $returnItem = $items[0]; 
                    
                    $tglRetur = Carbon::parse($trans->tgl_transaksi)->addDays(rand(1,5));
                    
                    $rid = DB::table('retur_penjualan')->insertGetId([
                        'id_penjualan' => $trans->id_penjualan,
                        'id_pelanggan' => $trans->id_pelanggan,
                        'id_toko' => $trans->id_toko,
                        'id_user' => $trans->id_user,
                        'tgl_retur' => $tglRetur,
                        'total_retur' => $returnItem['subtotal'],
                        'status_retur' => 'Selesai',
                        'keterangan' => 'Retur Dummy',
                        'created_at' => $tglRetur,
                        'updated_at' => $tglRetur,
                    ]);

                    $returDetailData[] = [
                        'id_retur_penjualan' => $rid,
                        'id_produk' => $returnItem['id_produk'],
                        'qty' => $returnItem['qty'],
                        'harga_satuan' => $returnItem['harga'],
                        'subtotal' => $returnItem['subtotal'],
                        'alasan' => 'Rusak/Cacat',
                        'created_at' => $tglRetur,
                        'updated_at' => $tglRetur,
                    ];
                }
                if(!empty($returDetailData)) DB::table('retur_penjualan_detail')->insertOrIgnore($returDetailData);
            }
        }
        
        $this->command->info("Status: Massive Seeding V3.2 Finished! (4k/day for 25 days)");
    }
}
