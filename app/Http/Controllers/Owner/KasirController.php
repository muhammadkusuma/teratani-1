<?php
namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\KartuPiutang;
use App\Models\LogStok;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\PenjualanDetail; // Pastikan model ini di-use
use App\Models\Produk;
use App\Models\StokToko;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    private function getTokoAktif()
    {
        if (session()->has('id_toko_aktif')) {
            return session('id_toko_aktif');
        }

        $user   = Auth::user();
        $tenant = $user->tenants()->first();

        if ($tenant) {
            $toko = Toko::where('id_tenant', $tenant->id_tenant)->first();
            if ($toko) {
                session(['id_toko_aktif' => $toko->id_toko]);
                return $toko->id_toko;
            }
        }
        return null;
    }

    public function index()
    {
        $id_toko = $this->getTokoAktif();

        if (! $id_toko) {
            return redirect()->back()->with('error', 'Anda belum memiliki toko.');
        }

        $toko      = Toko::find($id_toko);
        $nama_toko = $toko ? $toko->nama_toko : 'Toko Tidak Diketahui';

        // Load awal: Hanya produk dengan stok > 0 agar rapi
        $produk = Produk::whereHas('stokToko', function ($q) use ($id_toko) {
            $q->where('id_toko', $id_toko)->where('stok_fisik', '>', 0);
        })->with(['stokToko' => function ($q) use ($id_toko) {
            $q->where('id_toko', $id_toko);
        }, 'satuanKecil'])->limit(20)->get();

        $pelanggan = Pelanggan::where('id_tenant', Auth::user()->tenants()->first()->id_tenant ?? 0)->get();

        return view('owner.kasir.index', compact('produk', 'pelanggan', 'nama_toko'));
    }

    public function searchProduk(Request $request)
    {
        $id_toko = $this->getTokoAktif();
        if (! $id_toko) {
            return response()->json([]);
        }

        $keyword = $request->get('keyword');

        $query = Produk::whereHas('stokToko', function ($q) use ($id_toko) {
            $q->where('id_toko', $id_toko);
        });

        if (! empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('nama_produk', 'LIKE', "%{$keyword}%")
                    ->orWhere('sku', 'LIKE', "%{$keyword}%")
                    ->orWhere('barcode', 'LIKE', "%{$keyword}%");
            });
        }

        $produk = $query->with(['stokToko' => function ($q) use ($id_toko) {
            $q->where('id_toko', $id_toko);
        }, 'satuanKecil'])->limit(20)->get();

        return response()->json($produk);
    }

    public function store(Request $request)
    {
        $request->validate([
            'items'        => 'required|array',
            'items.*.id'   => 'required|exists:produk,id_produk',
            'items.*.qty'  => 'required|numeric|min:1',
            'bayar'        => 'required|numeric|min:0',
            'metode_bayar' => 'required|in:Tunai,Transfer,Hutang',
        ]);

        $id_toko = $this->getTokoAktif();
        if (! $id_toko) {
            return response()->json(['status' => 'error', 'message' => 'Sesi toko kadaluarsa.'], 403);
        }

        $user = Auth::user();

        DB::beginTransaction();
        try {
            $total_bruto = 0;
            $items_fix   = [];

            // 1. Cek Stok & Hitung
            foreach ($request->items as $item) {
                $produk = Produk::with(['stokToko' => function ($q) use ($id_toko) {
                    $q->where('id_toko', $id_toko);
                }])->find($item['id']);

                if (! $produk) {
                    continue;
                }

                $stok_sekarang = $produk->stokToko->stok_fisik ?? 0;

                if ($stok_sekarang < $item['qty']) {
                    throw new \Exception("Stok {$produk->nama_produk} kurang (Sisa: $stok_sekarang).");
                }

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

            // 2. Hitung Pembayaran
            $diskon      = $request->diskon ?? 0;
            $pajak       = 0;
            $total_netto = ($total_bruto - $diskon) + $pajak;
            $bayar       = $request->bayar;
            $metode      = $request->metode_bayar;

            $status_bayar = 'Lunas';
            $kembalian    = $bayar - $total_netto;

            if ($metode == 'Hutang') {
                if (empty($request->id_pelanggan)) {
                    throw new \Exception("Transaksi Hutang WAJIB memilih Pelanggan.");
                }

                if ($bayar >= $total_netto) {
                    // Jika pilih hutang tapi bayar lunas, anggap Tunai/Lunas tapi metode tetap tercatat
                    $status_bayar = 'Lunas';
                } else {
                    $status_bayar = 'Belum Lunas';
                    $kembalian    = 0;
                }
            } else {
                if ($kembalian < 0) {
                    throw new \Exception("Uang kurang Rp " . number_format(abs($kembalian), 0, ',', '.'));
                }
            }

            // 3. Simpan Penjualan
            $penjualan = Penjualan::create([
                'id_toko'          => $id_toko,
                'id_user'          => $user->id_user,
                'id_pelanggan'     => $request->id_pelanggan,
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

            // 4. [FIX] Simpan Kartu Piutang jika Hutang
            if ($metode == 'Hutang' && $status_bayar == 'Belum Lunas') {
                // Pastikan Anda memiliki model KartuPiutang dan tabelnya
                // Sesuaikan nama kolom dengan database Anda
                KartuPiutang::create([
                    'id_toko'         => $id_toko,
                    'id_pelanggan'    => $request->id_pelanggan,
                    'id_penjualan'    => $penjualan->id_penjualan,
                    'tanggal_piutang' => now(),
                    'jumlah_piutang'  => $total_netto - $bayar, // Sisa yang belum dibayar
                    'sisa_piutang'    => $total_netto - $bayar,
                    'status'          => 'Belum Lunas',
                    'keterangan'      => 'Penjualan Kasir ' . $penjualan->no_faktur,
                ]);
            }

            // 5. Simpan Detail & Update Stok
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

                $stokToko = StokToko::where('id_toko', $id_toko)
                    ->where('id_produk', $data['produk']->id_produk)
                    ->first();

                if ($stokToko) {
                    $stok_awal = $stokToko->stok_fisik;
                    $stokToko->decrement('stok_fisik', $data['qty']);

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
                'kembalian'    => $kembalian,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * [FIX] Method Cetak Struk
     */
    public function print($id)
    {
        $id_toko = $this->getTokoAktif();
        if (! $id_toko) {
            abort(403);
        }

        $penjualan = Penjualan::with(['details.produk', 'pelanggan', 'user'])
            ->where('id_toko', $id_toko)
            ->findOrFail($id);

        $toko = Toko::find($id_toko);

        // Pastikan Anda membuat view ini
        return view('owner.kasir.struk', compact('penjualan', 'toko'));
    }
}
