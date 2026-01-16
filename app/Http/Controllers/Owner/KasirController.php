<?php
namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
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
     * Helper: ambil ID toko yang sedang aktif di session
     */
    private function getTokoAktif()
    {
        return session('toko_active_id');
    }

    public function index()
    {
        $id_toko = $this->getTokoAktif();

        if (!$id_toko) {
            return redirect()->route('owner.toko.index')->with('error', 'Pilih toko terlebih dahulu');
        }

        $toko = Toko::find($id_toko);

        if (!$toko) {
            return redirect()->route('owner.toko.index')->with('error', 'Toko tidak ditemukan');
        }

        // Limit to 50 most recent active products with stock
        $produk = Produk::where('is_active', 1)
            ->whereHas('stokToko', function ($q) use ($id_toko) {
                $q->where('id_toko', $id_toko)->where('stok_fisik', '>', 0);
            })
            ->with(['stokToko' => function ($q) use ($id_toko) {
                $q->where('id_toko', $id_toko);
            }, 'satuanKecil'])
            ->orderBy('nama_produk')
            ->limit(50)
            ->get();

        $metodeBayar = ['Tunai', 'Transfer', 'Hutang'];

        // Select only needed columns for pelanggan dropdown
        $pelanggan = Pelanggan::where('id_toko', $id_toko)
            ->select('id_pelanggan', 'kode_pelanggan', 'nama_pelanggan', 'kategori_harga')
            ->orderBy('nama_pelanggan')
            ->get();

        return view('owner.kasir.index', compact('toko', 'produk', 'metodeBayar', 'pelanggan'));
    }

    public function searchProduk(Request $request)
    {
        $id_toko = $this->getTokoAktif();
        if (! $id_toko) {
            return response()->json([]);
        }

        $keyword = $request->get('keyword');

        $query = Produk::where('is_active', 1)
            ->whereHas('stokToko', function ($q) use ($id_toko) {
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


            $pelanggan = null;
            if ($request->id_pelanggan) {
                $pelanggan = Pelanggan::find($request->id_pelanggan);
            }
            
            // Prioritize the category sent from frontend (which matches what user saw), otherwise fallback to customer default or umum
            $kategoriHarga = $request->kategori_harga ?? ($pelanggan ? $pelanggan->kategori_harga : 'umum');

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

                // Determine price based on category
                $harga = $produk->harga_jual_umum;
                if ($kategoriHarga == 'grosir' && $produk->harga_jual_grosir) $harga = $produk->harga_jual_grosir;
                if ($kategoriHarga == 'r1' && $produk->harga_r1) $harga = $produk->harga_r1;
                if ($kategoriHarga == 'r2' && $produk->harga_r2) $harga = $produk->harga_r2;

                $subtotal    = $harga * $item['qty'];
                $total_bruto += $subtotal;

                $items_fix[]  = [
                    'produk'   => $produk,
                    'qty'      => $item['qty'],
                    'harga'    => $harga,
                    'subtotal' => $subtotal,
                ];
            }

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

                $pelanggan = Pelanggan::find($request->id_pelanggan);
                if (! $pelanggan) {
                    throw new \Exception("Data pelanggan tidak valid.");
                }

                if ($bayar < $total_netto) {
                    $status_bayar   = 'Belum Lunas';
                    $kembalian      = 0;
                } else {
                    $status_bayar = 'Lunas';
                }
            } else {
                if ($kembalian < 0) {
                    throw new \Exception("Uang pembayaran kurang Rp " . number_format(abs($kembalian), 0, ',', '.'));
                }
            }

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

            if ($metode == 'Hutang' && $status_bayar == 'Belum Lunas') {
            }

            foreach ($items_fix as $data) {
                PenjualanDetail::create([
                    'id_penjualan'          => $penjualan->id_penjualan,
                    'id_produk'             => $data['produk']->id_produk,
                    'qty'                   => $data['qty'],
                    'satuan_jual'           => $data['produk']->satuanKecil->nama_satuan ?? 'Pcs',
                    'harga_modal_saat_jual' => $data['produk']->harga_beli_rata_rata ?? 0,
                    'harga_jual_satuan'     => $data['harga'],
                    'subtotal'              => $data['subtotal'],
                ]);

                $stokToko = StokToko::where('id_toko', $id_toko)
                    ->where('id_produk', $data['produk']->id_produk)
                    ->first();

                if ($stokToko) {
                    $stokToko->decrement('stok_fisik', $data['qty']);
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

    public function print($id)
    {
        $id_toko = $this->getTokoAktif();
        if (! $id_toko) {
            abort(403, 'Akses Toko Ditolak');
        }

        $penjualan = Penjualan::with(['details.produk', 'pelanggan', 'user'])
            ->where('id_toko', $id_toko)
            ->findOrFail($id);

        $toko = Toko::find($id_toko);

        return view('owner.kasir.struk', compact('penjualan', 'toko'));
    }

    public function cetakFaktur($id)
    {
        $transaksi = Penjualan::with(['details.produk', 'pelanggan', 'toko', 'user'])
            ->findOrFail($id);

        $terbilang = $this->terbilang($transaksi->total_netto) . ' Rupiah';

        return view('owner.kasir.faktur', compact('transaksi', 'terbilang'));
    }

    private function terbilang($nilai)
    {
        $nilai = abs($nilai);
        $huruf = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
        $temp  = "";
        if ($nilai < 12) {
            $temp = " " . $huruf[$nilai];
        } else if ($nilai < 20) {
            $temp = $this->terbilang($nilai - 10) . " Belas";
        } else if ($nilai < 100) {
            $temp = $this->terbilang($nilai / 10) . " Puluh" . $this->terbilang($nilai % 10);
        } else if ($nilai < 200) {
            $temp = " Seratus" . $this->terbilang($nilai - 100);
        } else if ($nilai < 1000) {
            $temp = $this->terbilang($nilai / 100) . " Ratus" . $this->terbilang($nilai % 100);
        } else if ($nilai < 2000) {
            $temp = " Seribu" . $this->terbilang($nilai - 1000);
        } else if ($nilai < 1000000) {
            $temp = $this->terbilang($nilai / 1000) . " Ribu" . $this->terbilang($nilai % 1000);
        } else if ($nilai < 1000000000) {
            $temp = $this->terbilang($nilai / 1000000) . " Juta" . $this->terbilang($nilai % 1000000);
        }
        return $temp;
    }


    /**
     * Menampilkan halaman riwayat transaksi hari ini / terbaru
     */
    public function riwayat(Request $request)
    {
        $id_toko = $this->getTokoAktif();
        if (! $id_toko) {
            return redirect()->back()->with('error', 'Akses Toko Ditolak');
        }

        $tanggal = $request->get('tanggal', date('Y-m-d'));

        $transaksi = Penjualan::with(['pelanggan', 'user'])
            ->where('id_toko', $id_toko)
            ->whereDate('tgl_transaksi', $tanggal)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('owner.kasir.riwayat', compact('transaksi', 'tanggal'));
    }

}
