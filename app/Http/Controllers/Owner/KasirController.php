<?php
namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\LogStok;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use App\Models\StokToko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    public function index()
    {
        // 1. Ambil ID Toko dari session
        $id_toko = session('id_toko_aktif');

        // 2. Jika Session Kosong, cari Toko secara otomatis via Relasi User -> Tenant -> Toko
        if (! $id_toko) {
            $user = Auth::user();
            // Ambil tenant pertama yang dimiliki user
            $tenant = $user->tenants()->first();

            if ($tenant) {
                // Ambil toko pertama milik tenant tersebut
                $tokoPertama = \App\Models\Toko::where('id_tenant', $tenant->id_tenant)->first();

                if ($tokoPertama) {
                    $id_toko = $tokoPertama->id_toko;
                    session(['id_toko_aktif' => $id_toko]);
                } else {
                    // User punya tenant tapi belum punya toko
                    return redirect()->back()->with('error', 'Bisnis Anda belum memiliki toko. Silakan buat toko terlebih dahulu.');
                }
            } else {
                // User belum punya tenant (bisnis)
                return redirect()->back()->with('error', 'Akun Anda belum terhubung dengan bisnis apapun.');
            }
        }

        // 3. Ambil Produk
        // Menggunakan 'stok_fisik' sesuai migrasi inventory
        $produk = Produk::whereHas('stokToko', function ($q) use ($id_toko) {
            $q->where('id_toko', $id_toko)
                ->where('stok_fisik', '>', 0);
        })->with(['stokToko' => function ($q) use ($id_toko) {
            $q->where('id_toko', $id_toko);
        }, 'satuanKecil'])->get(); // Eager load satuanKecil juga

        $pelanggan = Pelanggan::where('id_tenant', Auth::user()->tenants()->first()->id_tenant ?? 0)->get();

        return view('owner.kasir.index', compact('produk', 'pelanggan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items'       => 'required|array',
            'items.*.id'  => 'required|exists:produk,id_produk',
            'items.*.qty' => 'required|integer|min:1',
            'bayar'       => 'required|numeric|min:0',
        ]);

        $id_toko = session('id_toko_aktif');
        if (! $id_toko) {
            return response()->json(['status' => 'error', 'message' => 'Sesi toko habis, silakan refresh halaman.'], 403);
        }

        $user = Auth::user();

        DB::beginTransaction();
        try {
            $total_bruto = 0;
            $items_fix   = [];

            foreach ($request->items as $item) {
                $produk = Produk::with(['stokToko' => function ($q) use ($id_toko) {
                    $q->where('id_toko', $id_toko);
                }, 'satuanKecil'])->find($item['id']);

                // Cek Stok (Gunakan stok_fisik)
                $stok_sekarang = $produk->stokToko->stok_fisik ?? 0;

                if ($stok_sekarang < $item['qty']) {
                    throw new \Exception("Stok produk {$produk->nama_produk} tidak cukup (Sisa: $stok_sekarang).");
                }

                // Ambil Harga (Gunakan harga_jual_umum)
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

            $pajak       = 0;
            $diskon      = $request->diskon ?? 0;
            $total_netto = ($total_bruto - $diskon) + $pajak;
            $kembalian   = $request->bayar - $total_netto;

            if ($kembalian < 0 && $request->metode_bayar == 'Tunai') {
                throw new \Exception("Uang pembayaran kurang.");
            }

            // Simpan Penjualan
            $penjualan = Penjualan::create([
                'id_toko'          => $id_toko,
                'id_user'          => $user->id_user,
                'id_pelanggan'     => $request->id_pelanggan,
                'no_faktur'        => 'INV/' . date('Ymd') . '/' . rand(1000, 9999),
                'tgl_transaksi'    => now(),
                'total_bruto'      => $total_bruto,
                'diskon_nota'      => $diskon,
                'pajak_ppn'        => $pajak,
                'total_netto'      => $total_netto,
                'jumlah_bayar'     => $request->bayar,
                'kembalian'        => max(0, $kembalian),
                'metode_bayar'     => $request->metode_bayar ?? 'Tunai',
                'status_transaksi' => 'Selesai',
                'status_bayar'     => ($kembalian >= 0) ? 'Lunas' : 'Belum Lunas',
            ]);

            // Simpan Detail & Update Stok
            foreach ($items_fix as $data) {
                PenjualanDetail::create([
                    'id_penjualan'          => $penjualan->id_penjualan,
                    'id_produk'             => $data['produk']->id_produk,
                    'qty'                   => $data['qty'],
                    'satuan_jual'           => $data['produk']->satuanKecil->nama_satuan ?? 'Pcs',
                    'harga_modal_saat_jual' => 0,
                    'harga_jual_satuan'     => $data['harga'],
                    'subtotal'              => $data['subtotal'],
                ]);

                // Update Stok Toko (Gunakan stok_fisik)
                $stokToko = StokToko::where('id_toko', $id_toko)
                    ->where('id_produk', $data['produk']->id_produk)
                    ->first();

                $stok_awal = $stokToko->stok_fisik;
                $stokToko->decrement('stok_fisik', $data['qty']);

                // Catat Log
                LogStok::create([
                    'id_toko'         => $id_toko,
                    'id_produk'       => $data['produk']->id_produk,
                    'id_user'         => $user->id_user,
                    'jenis_transaksi' => 'Penjualan',
                    'no_referensi'    => $penjualan->no_faktur,
                    'qty_masuk'       => 0,
                    'qty_keluar'      => $data['qty'],
                    'stok_akhir'      => $stok_awal - $data['qty'],
                    'keterangan'      => 'Penjualan Kasir',
                ]);
            }

            DB::commit();
            return response()->json([
                'status'       => 'success',
                'message'      => 'Transaksi Berhasil',
                'id_penjualan' => $penjualan->id_penjualan,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // --- TAMBAHAN BARU: Method Search AJAX ---
    // --- TAMBAHAN BARU: Method Search AJAX (DIPERBAIKI) ---
    public function searchProduk(Request $request)
    {
        $keyword = $request->get('keyword');
        
        // REVISI: Mengambil ID Toko langsung dari session, karena getTokoAktif() tidak ada
        $id_toko = session('id_toko_aktif');

        if (! $id_toko) {
            return response()->json([]);
        }

        $query = Produk::whereHas('stokToko', function ($q) use ($id_toko) {
            $q->where('id_toko', $id_toko)->where('stok_fisik', '>', 0);
        });

        // Logika pencarian: Nama, SKU, atau Barcode
        if (! empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('nama_produk', 'LIKE', "%{$keyword}%")
                    ->orWhere('sku', 'LIKE', "%{$keyword}%")
                    ->orWhere('barcode', 'LIKE', "%{$keyword}%");
            });
        }

        $produk = $query->with(['stokToko' => function ($q) use ($id_toko) {
            $q->where('id_toko', $id_toko);
        }, 'satuanKecil'])
            ->limit(20) // Batasi hasil pencarian maksimal 20 agar ringan
            ->get();

        return response()->json($produk);
    }
}
