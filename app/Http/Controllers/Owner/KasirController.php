<?php
namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\KartuPiutang;
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
     * Mendapatkan ID Toko yang sedang aktif.
     * Logika: Session -> Toko Pusat -> Toko Pertama
     */
    private function getTokoAktif()
    {
        // 1. Cek apakah sudah ada di session (Gunakan key yang konsisten: 'toko_active_id')
        if (session()->has('toko_active_id')) {
            return session('toko_active_id');
        }

        $user   = Auth::user();
        $tenant = $user->tenants()->first();

        if (! $tenant) {
            return null;
        }

        // 3. Cari Toko Default
        $toko = Toko::where('id_tenant', $tenant->id_tenant)
            ->orderBy('is_pusat', 'desc')
            ->orderBy('id_toko', 'asc')
            ->first();

        if ($toko) {
            // PERBAIKAN: Gunakan key session yang sama dengan TokoController
            session([
                'toko_active_id'   => $toko->id_toko,
                'toko_active_nama' => $toko->nama_toko,
            ]);
            return $toko->id_toko;
        }

        return null;
    }

    public function index()
    {
        $id_toko = $this->getTokoAktif();

        if (! $id_toko) {
            return redirect()->back()->with('error', 'Akun Anda belum terhubung dengan Toko/Cabang manapun.');
        }

        $toko      = Toko::find($id_toko);
        $nama_toko = $toko ? $toko->nama_toko : 'Toko Tidak Diketahui';

        // PERBAIKAN: Tambahkan where('is_active', 1)
        // Load awal: Hanya produk dengan stok > 0 DAN is_active = 1
        $produk = Produk::where('is_active', 1)
            ->whereHas('stokToko', function ($q) use ($id_toko) {
                $q->where('id_toko', $id_toko)->where('stok_fisik', '>', 0);
            })
            ->with(['stokToko' => function ($q) use ($id_toko) {
                $q->where('id_toko', $id_toko);
            }, 'satuanKecil'])
            ->limit(20)
            ->get();

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

        // PERBAIKAN: Tambahkan where('is_active', 1) pada query dasar
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
            'metode_bayar' => 'required|in:Tunai,Transfer,Hutang', // Validasi input
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

            // 1. Cek Stok & Hitung Total
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

                $harga       = $produk->harga_jual_umum;
                $subtotal    = $harga * $item['qty'];
                $total_bruto += $subtotal;

                $items_fix[]  = [
                    'produk'   => $produk,
                    'qty'      => $item['qty'],
                    'harga'    => $harga,
                    'subtotal' => $subtotal,
                ];
            }

            // 2. Kalkulasi Angka
            $diskon      = $request->diskon ?? 0;
            $pajak       = 0;
            $total_netto = ($total_bruto - $diskon) + $pajak;
            $bayar       = $request->bayar;
            $metode      = $request->metode_bayar;

            $status_bayar = 'Lunas';
            $kembalian    = $bayar - $total_netto;

            // 3. LOGIKA KHUSUS HUTANG & LIMIT PIUTANG
            if ($metode == 'Hutang') {
                if (empty($request->id_pelanggan)) {
                    throw new \Exception("Transaksi Hutang WAJIB memilih Pelanggan.");
                }

                $pelanggan = Pelanggan::find($request->id_pelanggan);
                if (! $pelanggan) {
                    throw new \Exception("Data pelanggan tidak valid.");
                }

                // Cek apakah bayar kurang dari total (benar-benar hutang)
                if ($bayar < $total_netto) {
                    $status_bayar   = 'Belum Lunas';
                    $kembalian      = 0;
                    $nominal_hutang = $total_netto - $bayar;

                    // === FITUR LIMIT PIUTANG ===
                    // Hitung total hutang yang belum lunas dari pelanggan ini
                    $total_hutang_berjalan = KartuPiutang::where('id_pelanggan', $pelanggan->id_pelanggan)
                        ->where('status', 'Belum Lunas')
                        ->sum('sisa_piutang');

                    // Hitung sisa limit yang tersedia
                    $sisa_limit = $pelanggan->limit_piutang - $total_hutang_berjalan;

                    // Cek apakah penambahan hutang baru melebihi sisa limit
                    if ($nominal_hutang > $sisa_limit) {
                        $format_sisa   = number_format($sisa_limit, 0, ',', '.');
                        $format_hutang = number_format($total_hutang_berjalan, 0, ',', '.');
                        $format_limit  = number_format($pelanggan->limit_piutang, 0, ',', '.');

                        throw new \Exception(
                            "Limit Piutang Tidak Mencukupi!\n\n" .
                            "Limit Pelanggan: Rp $format_limit\n" .
                            "Hutang Berjalan: Rp $format_hutang\n" .
                            "Sisa Limit: Rp $format_sisa\n" .
                            "Transaksi ini butuh: Rp " . number_format($nominal_hutang, 0, ',', '.')
                        );
                    }
                    // === END FITUR LIMIT PIUTANG ===
                } else {
                    // Jika user pilih 'Hutang' tapi bayarnya Lunas/Lebih
                    $status_bayar = 'Lunas';
                }
            } else {
                // Metode Tunai/Transfer
                if ($kembalian < 0) {
                    throw new \Exception("Uang pembayaran kurang Rp " . number_format(abs($kembalian), 0, ',', '.'));
                }
            }

            // 4. Simpan Penjualan
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
                'metode_bayar'     => $metode, // Sekarang 'Hutang' bisa masuk
                'status_transaksi' => 'Selesai',
                'status_bayar'     => $status_bayar,
            ]);

            // 5. Simpan Kartu Piutang
            if ($metode == 'Hutang' && $status_bayar == 'Belum Lunas') {
                KartuPiutang::create([
                    'id_toko'         => $id_toko,
                    'id_pelanggan'    => $request->id_pelanggan,
                    'id_penjualan'    => $penjualan->id_penjualan,
                    'tanggal_piutang' => now(),                 // PERBAIKAN: tanggal_piutang bukan tgl_jatuh_tempo
                    'tgl_jatuh_tempo' => now()->addDays(30),    // Simpan tanggal jatuh tempo
                    'total_piutang'   => $total_netto - $bayar, // PERBAIKAN: Simpan total awal
                    'jumlah_piutang'  => 0,                     // Field ini mungkin redundant jika ada total_piutang, sesuaikan dengan struktur tabel
                    'sudah_dibayar'   => 0,
                    'sisa_piutang'    => $total_netto - $bayar,
                    'status'          => 'Belum Lunas',
                    // 'keterangan'      => 'Penjualan Kasir ...' // Opsional jika ada kolom keterangan
                ]);

                // Note: Pastikan field di create() sesuai dengan migration KartuPiutang
            }

            // 6. Simpan Detail & Kurangi Stok
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

                // Update Stok
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

    public function print($id)
    {
        $id_toko = $this->getTokoAktif();
        if (! $id_toko) {
            abort(403, 'Akses Toko Ditolak');
        }

        // Pastikan penjualan milik toko yang sedang aktif
        $penjualan = Penjualan::with(['details.produk', 'pelanggan', 'user'])
            ->where('id_toko', $id_toko)
            ->findOrFail($id);

        $toko = Toko::find($id_toko);

        return view('owner.kasir.struk', compact('penjualan', 'toko'));
    }

    public function cetakFaktur($id)
    {
        // Pastikan load relasi pelanggan lengkap
        $transaksi = Penjualan::with(['details.produk', 'pelanggan', 'toko', 'user'])
            ->findOrFail($id);

        // Generate terbilang
        $terbilang = $this->terbilang($transaksi->total_netto) . ' Rupiah';

        // Return ke view faktur
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

    // ... method index() dan searchProduk() yang sudah ada ...

    /**
     * Menampilkan halaman riwayat transaksi hari ini / terbaru
     */
    public function riwayat(Request $request)
    {
        $id_toko = $this->getTokoAktif();
        if (! $id_toko) {
            return redirect()->back()->with('error', 'Akses Toko Ditolak');
        }

        // Ambil filter tanggal dari request, default hari ini
        $tanggal = $request->get('tanggal', date('Y-m-d'));

        $transaksi = Penjualan::with(['pelanggan', 'user'])
            ->where('id_toko', $id_toko)
            ->whereDate('tgl_transaksi', $tanggal)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('owner.kasir.riwayat', compact('transaksi', 'tanggal'));
    }

    // ... method store(), print(), dll ...
}
