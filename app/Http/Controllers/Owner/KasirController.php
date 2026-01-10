<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\LogStok;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use App\Models\StokToko;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    /**
     * Helper: Mendapatkan ID Toko yang sedang aktif.
     * Mengambil dari session, atau mencari default toko pertama milik user.
     */
    private function getTokoAktif()
    {
        // 1. Cek session
        if (session()->has('id_toko_aktif')) {
            return session('id_toko_aktif');
        }

        // 2. Jika tidak ada di session, cari toko user dari database
        $user = Auth::user();
        $tenant = $user->tenants()->first(); // Asumsi user punya tenant

        if ($tenant) {
            $toko = Toko::where('id_tenant', $tenant->id_tenant)->first();
            if ($toko) {
                // Simpan ke session agar request selanjutnya lebih ringan
                session(['id_toko_aktif' => $toko->id_toko]);
                return $toko->id_toko;
            }
        }

        return null; // Tidak ada toko
    }

    /**
     * Halaman Utama Kasir
     */
    public function index()
    {
        $id_toko = $this->getTokoAktif();

        if (!$id_toko) {
            return redirect()->back()->with('error', 'Anda belum memiliki toko. Silakan buat toko terlebih dahulu di menu Bisnis.');
        }

        // Ambil Nama Toko untuk ditampilkan di UI
        $toko = Toko::find($id_toko);
        $nama_toko = $toko ? $toko->nama_toko : 'Toko Tidak Diketahui';

        // Ambil 20 Produk Terbaru untuk tampilan awal
        // Kita filter stok > 0 untuk tampilan awal agar rapi, 
        // tapi di pencarian (searchProduk) kita akan tampilkan semua.
        $produk = Produk::whereHas('stokToko', function ($q) use ($id_toko) {
            $q->where('id_toko', $id_toko)
              ->where('stok_fisik', '>', 0);
        })->with(['stokToko' => function ($q) use ($id_toko) {
            $q->where('id_toko', $id_toko);
        }, 'satuanKecil'])
        ->limit(20)
        ->get();

        // Ambil data pelanggan untuk dropdown
        $pelanggan = Pelanggan::where('id_tenant', Auth::user()->tenants()->first()->id_tenant ?? 0)->get();

        return view('owner.kasir.index', compact('produk', 'pelanggan', 'nama_toko'));
    }

    /**
     * Pencarian Produk Realtime (AJAX)
     */
    public function searchProduk(Request $request)
    {
        $id_toko = $this->getTokoAktif();
        if (!$id_toko) return response()->json([]);

        $keyword = $request->get('keyword');

        // Query dasar: Produk harus terdaftar di toko ini (ada record di stok_toko)
        $query = Produk::whereHas('stokToko', function ($q) use ($id_toko) {
            $q->where('id_toko', $id_toko);
            // CATATAN: Kita HAPUS 'where stok_fisik > 0' disini 
            // agar produk yang stoknya 0 tetap muncul saat dicari (biar kasir tau barangnya ada tapi habis)
        });

        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('nama_produk', 'LIKE', "%{$keyword}%")
                  ->orWhere('sku', 'LIKE', "%{$keyword}%")
                  ->orWhere('barcode', 'LIKE', "%{$keyword}%");
            });
        }

        $produk = $query->with(['stokToko' => function ($q) use ($id_toko) {
            $q->where('id_toko', $id_toko);
        }, 'satuanKecil'])
        ->limit(20) // Batasi hasil agar tidak berat
        ->get();

        return response()->json($produk);
    }

    /**
     * Proses Simpan Transaksi (Checkout)
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'items'        => 'required|array',
            'items.*.id'   => 'required|exists:produk,id_produk',
            'items.*.qty'  => 'required|numeric|min:1',
            'bayar'        => 'required|numeric|min:0',
            'metode_bayar' => 'required|in:Tunai,Transfer,Hutang',
        ]);

        $id_toko = $this->getTokoAktif();
        if (!$id_toko) {
            return response()->json(['status' => 'error', 'message' => 'Sesi toko kadaluarsa. Silakan refresh halaman.'], 403);
        }

        $user = Auth::user();

        DB::beginTransaction();
        try {
            $total_bruto = 0;
            $items_fix   = [];

            // 2. Loop Items untuk Cek Stok & Hitung Total
            foreach ($request->items as $item) {
                // Ambil produk beserta stok di toko ini
                $produk = Produk::with(['stokToko' => function ($q) use ($id_toko) {
                    $q->where('id_toko', $id_toko);
                }, 'satuanKecil'])->find($item['id']);

                if (!$produk) continue;

                $stok_sekarang = $produk->stokToko->stok_fisik ?? 0;

                // Cek Ketersediaan Stok
                if ($stok_sekarang < $item['qty']) {
                    throw new \Exception("Stok produk '{$produk->nama_produk}' tidak mencukupi (Tersedia: $stok_sekarang).");
                }

                // Hitung Subtotal
                $harga    = $produk->harga_jual_umum;
                $subtotal = $harga * $item['qty'];
                $total_bruto += $subtotal;

                $items_fix[] = [
                    'produk'   => $produk,
                    'qty'      => $item['qty'],
                    'harga'    => $harga,
                    'subtotal' => $subtotal,
                ];
            }

            // 3. Logika Pembayaran
            $pajak       = 0; 
            $diskon      = $request->diskon ?? 0;
            $total_netto = ($total_bruto - $diskon) + $pajak;
            $bayar       = $request->bayar;
            $kembalian   = $bayar - $total_netto;
            $metode      = $request->metode_bayar;
            
            $status_bayar = 'Lunas'; // Default

            if ($metode == 'Hutang') {
                // Validasi Hutang
                if (empty($request->id_pelanggan)) {
                    throw new \Exception("Transaksi Hutang WAJIB memilih data Pelanggan.");
                }
                
                // Status Lunas jika bayar >= total, selain itu Belum Lunas
                if ($bayar >= $total_netto) {
                    $status_bayar = 'Lunas';
                    // Kembalian tetap dihitung
                } else {
                    $status_bayar = 'Belum Lunas';
                    $kembalian = 0; // Tidak ada kembalian untuk hutang
                }
            } else {
                // Tunai / Transfer
                if ($kembalian < 0) {
                    throw new \Exception("Uang pembayaran kurang Rp " . number_format(abs($kembalian), 0, ',', '.'));
                }
            }

            // 4. Simpan Header Penjualan
            $penjualan = Penjualan::create([
                'id_toko'          => $id_toko,
                'id_user'          => $user->id_user,
                'id_pelanggan'     => $request->id_pelanggan, // Nullable
                'no_faktur'        => 'INV/' . date('Ymd') . '/' . rand(1000, 9999),
                'tgl_transaksi'    => now(),
                'tgl_jatuh_tempo'  => ($metode == 'Hutang') ? now()->addDays(30) : null,
                'total_bruto'      => $total_bruto,
                'diskon_nota'      => $diskon,
                'pajak_ppn'        => $pajak,
                'total_netto'      => $total_netto,
                'jumlah_bayar'     => $bayar,
                'kembalian'        => max(0, $kembalian),
                'metode_bayar'     => $metode,
                'status_transaksi' => 'Selesai',
                'status_bayar'     => $status_bayar,
            ]);

            // 5. Simpan Detail & Potong Stok
            foreach ($items_fix as $data) {
                // Simpan Detail
                PenjualanDetail::create([
                    'id_penjualan'          => $penjualan->id_penjualan,
                    'id_produk'             => $data['produk']->id_produk,
                    'qty'                   => $data['qty'],
                    'satuan_jual'           => $data['produk']->satuanKecil->nama_satuan ?? 'Pcs',
                    'harga_modal_saat_jual' => 0, // Bisa diisi $data['produk']->harga_beli_terakhir
                    'harga_jual_satuan'     => $data['harga'],
                    'subtotal'              => $data['subtotal'],
                ]);

                // Update Stok Fisik
                $stokToko = StokToko::where('id_toko', $id_toko)
                    ->where('id_produk', $data['produk']->id_produk)
                    ->first();

                if ($stokToko) {
                    $stok_awal = $stokToko->stok_fisik;
                    $stokToko->decrement('stok_fisik', $data['qty']);

                    // Catat Log Stok
                    LogStok::create([
                        'id_toko'         => $id_toko,
                        'id_produk'       => $data['produk']->id_produk,
                        'id_user'         => $user->id_user,
                        'jenis_transaksi' => 'Penjualan',
                        'no_referensi'    => $penjualan->no_faktur,
                        'qty_masuk'       => 0,
                        'qty_keluar'      => $data['qty'],
                        'stok_akhir'      => $stok_awal - $data['qty'],
                        'keterangan'      => "Kasir ($metode)",
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'status'       => 'success',
                'message'      => 'Transaksi Berhasil Disimpan',
                'id_penjualan' => $penjualan->id_penjualan,
                'kembalian'    => $kembalian
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error', 
                'message' => $e->getMessage()
            ], 500);
        }
    }
}