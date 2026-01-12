<?php
namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Distributor;
use App\Models\LogStok;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Pengeluaran; // Tambahkan Model Pengeluaran
use App\Models\Produk;
use App\Models\Satuan;
use App\Models\StokToko;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PembelianController extends Controller
{
    public function index()
    {
        $id_toko = session('toko_active_id');

        $pembelians = Pembelian::with(['distributor', 'user'])
            ->where('id_toko', $id_toko)
            ->orderBy('tgl_pembelian', 'desc')
            ->paginate(10);

        return view('owner.pembelian.index', compact('pembelians'));
    }

    public function create()
    {
        $id_toko = session('toko_active_id');
        $toko    = Toko::findOrFail($id_toko);

        // Ambil data berdasarkan Tenant ID
        $distributors = Distributor::where('id_tenant', $toko->id_tenant)
            ->orderBy('nama_distributor', 'asc')
            ->get();

        $produks = Produk::where('id_tenant', $toko->id_tenant)
            ->where('is_active', true)
            ->orderBy('nama_produk', 'asc')
            ->get();

        return view('owner.pembelian.create', compact('distributors', 'produks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_distributor'     => 'required|exists:distributor,id_distributor',
            'no_faktur_supplier' => 'required|string|max:50',
            'tgl_pembelian'      => 'required|date',
            'status_bayar'       => 'required|in:Lunas,Hutang,Sebagian',
            'nominal_bayar'      => 'nullable|numeric|min:0', // Input baru untuk jumlah yang dibayar
            'produk_id'          => 'required|array',
            'produk_id.*'        => 'required|exists:produk,id_produk',
            'qty'                => 'required|array',
            'qty.*'              => 'required|integer|min:1',
            'harga_beli'         => 'required|array',
            'harga_beli.*'       => 'required|numeric|min:0',
        ]);

        $id_toko = session('toko_active_id');

        try {
            DB::beginTransaction();

            // 1. Hitung Total Pembelian
            $grandTotal = 0;
            foreach ($request->qty as $key => $val) {
                $grandTotal += ($val * $request->harga_beli[$key]);
            }

            // 2. Simpan Header Pembelian
            $pembelian = Pembelian::create([
                'id_toko'            => $id_toko,
                'id_distributor'     => $request->id_distributor,
                'id_user'            => Auth::id(),
                'no_faktur_supplier' => $request->no_faktur_supplier,
                'tgl_pembelian'      => $request->tgl_pembelian,
                'tgl_jatuh_tempo'    => $request->tgl_jatuh_tempo,
                'status_bayar'       => $request->status_bayar,
                'keterangan'         => $request->keterangan,
                'total_pembelian'    => $grandTotal,
            ]);

            // 3. Simpan Detail & Update Stok
            foreach ($request->produk_id as $index => $id_produk) {
                $qty      = $request->qty[$index];
                $harga    = $request->harga_beli[$index];
                $subtotal = $qty * $harga;
                $expired  = $request->tgl_expired[$index] ?? null;

                $produk = Produk::find($id_produk);

                // Cari nama satuan (opsional)
                $namaSatuan = 'Pcs';
                if ($produk->id_satuan_kecil) {
                    $satuanObj = Satuan::find($produk->id_satuan_kecil);
                    if ($satuanObj) {
                        $namaSatuan = $satuanObj->nama_satuan;
                    }

                }

                PembelianDetail::create([
                    'id_pembelian'      => $pembelian->id_pembelian,
                    'id_produk'         => $id_produk,
                    'qty'               => $qty,
                    'satuan_beli'       => $namaSatuan,
                    'harga_beli_satuan' => $harga,
                    'subtotal'          => $subtotal,
                    'tgl_expired_item'  => $expired,
                ]);

                // Update Harga Beli Rata-rata di Master Produk
                $produk->update(['harga_beli_rata_rata' => $harga]);

                // Update Stok Toko
                $stokToko = StokToko::firstOrCreate(
                    ['id_toko' => $id_toko, 'id_produk' => $id_produk],
                    ['stok' => 0]
                );
                $stokAwal = $stokToko->stok;
                $stokToko->increment('stok', $qty);

                // Catat Log Stok
                LogStok::create([
                    'id_toko'         => $id_toko,
                    'id_produk'       => $id_produk,
                    'id_user'         => Auth::id(),
                    'jenis_transaksi' => 'Pembelian',
                    'no_referensi'    => $pembelian->no_faktur_supplier,
                    'qty_masuk'       => $qty,
                    'qty_keluar'      => 0,
                    'stok_akhir'      => $stokAwal + $qty,
                    'keterangan'      => 'Pembelian ID: ' . $pembelian->id_pembelian,
                ]);
            }

            // 4. Integrasi ke Keuangan (Tabel Pengeluaran)
            // Jika status Lunas atau Sebagian, catat uang keluar
            $nominalBayar = $request->input('nominal_bayar', 0);

            // Override nominal jika status Lunas (bayar full)
            if ($request->status_bayar == 'Lunas') {
                $nominalBayar = $grandTotal;
            }

            if (($request->status_bayar == 'Lunas' || $request->status_bayar == 'Sebagian') && $nominalBayar > 0) {
                Pengeluaran::create([
                    'id_toko'         => $id_toko,
                    'id_user'         => Auth::id(),
                    'no_referensi'    => 'BYR-' . $pembelian->id_pembelian,
                    'tgl_pengeluaran' => $request->tgl_pembelian,
                    'kategori_biaya'  => 'Pembelian Stok',
                    'nominal'         => $nominalBayar,
                    'keterangan'      => 'Pembayaran Faktur Supplier: ' . $request->no_faktur_supplier,
                ]);
            }

            DB::commit();

            return redirect()->route('owner.pembelian.index')
                ->with('success', 'Pembelian berhasil disimpan. Stok bertambah & Keuangan tercatat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $pembelian = Pembelian::with(['details.produk', 'distributor', 'toko', 'user'])
            ->where('id_toko', session('toko_active_id'))
            ->findOrFail($id);

        return view('owner.pembelian.show', compact('pembelian'));
    }
}
