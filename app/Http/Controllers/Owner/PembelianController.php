<?php
namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Distributor;
use App\Models\LogStok;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Pengeluaran;
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
        $this->validateRequest($request);

        $id_toko = session('toko_active_id');

        try {
            DB::beginTransaction();

            // 1. Hitung Grand Total
            $grandTotal = 0;
            foreach ($request->qty as $key => $val) {
                $harga       = $request->harga_beli[$key] ?? 0;
                $grandTotal += ($val * $harga);
            }

            // 2. Simpan Header Pembelian
            $pembelian = Pembelian::create([
                'id_toko'            => $id_toko,
                'id_distributor'     => $request->id_distributor,
                'id_user'            => Auth::id(),
                'no_faktur_supplier' => $request->no_faktur_supplier,
                'tgl_pembelian'      => $request->tgl_pembelian,
                'tgl_jatuh_tempo'    => $request->status_bayar == 'Lunas' ? null : $request->tgl_jatuh_tempo,
                'status_bayar'       => $request->status_bayar,
                'keterangan'         => $request->keterangan,
                'total_pembelian'    => $grandTotal,
            ]);

            // 3. Simpan Detail & Update Stok
            $this->processDetailsAndStock($pembelian, $request, $id_toko, 'Baru');

            // 4. Catat Pengeluaran (Keuangan)
            $this->processKeuangan($pembelian, $request, $id_toko, $grandTotal);

            DB::commit();

            return redirect()->route('owner.pembelian.index')
                ->with('success', 'Pembelian berhasil disimpan. Stok telah bertambah.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $pembelian = Pembelian::with(['details.produk', 'distributor', 'toko', 'user'])
            ->where('id_toko', session('toko_active_id'))
            ->findOrFail($id);

        return view('owner.pembelian.show', compact('pembelian'));
    }

    public function edit($id)
    {
        $id_toko = session('toko_active_id');
        $toko    = Toko::findOrFail($id_toko);

        $pembelian = Pembelian::with('details')->where('id_toko', $id_toko)->findOrFail($id);

        $distributors = Distributor::where('id_tenant', $toko->id_tenant)->orderBy('nama_distributor', 'asc')->get();
        $produks      = Produk::where('id_tenant', $toko->id_tenant)->where('is_active', true)->orderBy('nama_produk', 'asc')->get();

        return view('owner.pembelian.edit', compact('pembelian', 'distributors', 'produks'));
    }

    public function update(Request $request, $id)
    {
        $this->validateRequest($request);

        $id_toko   = session('toko_active_id');
        $pembelian = Pembelian::where('id_toko', $id_toko)->findOrFail($id);

        try {
            DB::beginTransaction();

            // 1. ROLLBACK STOK LAMA (Gunakan stok_fisik)
            foreach ($pembelian->details as $detail) {
                $stokToko = StokToko::where('id_toko', $id_toko)->where('id_produk', $detail->id_produk)->first();
                if ($stokToko) {
                    // PERBAIKAN: Gunakan stok_fisik
                    $stokToko->decrement('stok_fisik', $detail->qty);

                    LogStok::create([
                        'id_toko'         => $id_toko, 'id_produk'           => $detail->id_produk, 'id_user' => Auth::id(),
                        'jenis_transaksi' => 'Mutasi Keluar', 'no_referensi' => $pembelian->no_faktur_supplier,
                        'qty_masuk'       => 0, 'qty_keluar'                 => $detail->qty,
                        'stok_akhir'      => $stokToko->stok_fisik, // PERBAIKAN
                        'keterangan'      => 'Revisi Pembelian (Rollback) #' . $pembelian->id_pembelian,
                    ]);
                }
            }

            // 2. Hapus Detail Lama & Pengeluaran Lama
            $pembelian->details()->delete();
            Pengeluaran::where('no_referensi', 'BYR-BELI-' . $pembelian->id_pembelian)->delete();

            // 3. Hitung Total Baru
            $grandTotal = 0;
            foreach ($request->qty as $key => $val) {
                $harga       = $request->harga_beli[$key] ?? 0;
                $grandTotal += ($val * $harga);
            }

            // 4. Update Header
            $pembelian->update([
                'id_distributor'     => $request->id_distributor,
                'no_faktur_supplier' => $request->no_faktur_supplier,
                'tgl_pembelian'      => $request->tgl_pembelian,
                'tgl_jatuh_tempo'    => $request->status_bayar == 'Lunas' ? null : $request->tgl_jatuh_tempo,
                'status_bayar'       => $request->status_bayar,
                'keterangan'         => $request->keterangan,
                'total_pembelian'    => $grandTotal,
            ]);

            // 5. Simpan Detail Baru & Tambah Stok Baru
            $this->processDetailsAndStock($pembelian, $request, $id_toko, 'Revisi');

            // 6. Catat Pengeluaran Baru
            $this->processKeuangan($pembelian, $request, $id_toko, $grandTotal);

            DB::commit();

            return redirect()->route('owner.pembelian.index')->with('success', 'Pembelian berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $id_toko   = session('toko_active_id');
        $pembelian = Pembelian::where('id_toko', $id_toko)->findOrFail($id);

        try {
            DB::beginTransaction();

            // 1. Rollback Stok (Gunakan stok_fisik)
            foreach ($pembelian->details as $detail) {
                $stokToko = StokToko::where('id_toko', $id_toko)->where('id_produk', $detail->id_produk)->first();
                if ($stokToko) {
                    // PERBAIKAN: Gunakan stok_fisik
                    $stokToko->decrement('stok_fisik', $detail->qty);

                    LogStok::create([
                        'id_toko'         => $id_toko, 'id_produk'           => $detail->id_produk, 'id_user' => Auth::id(),
                        'jenis_transaksi' => 'Mutasi Keluar', 'no_referensi' => $pembelian->no_faktur_supplier,
                        'qty_masuk'       => 0, 'qty_keluar'                 => $detail->qty,
                        'stok_akhir'      => $stokToko->stok_fisik, // PERBAIKAN
                        'keterangan'      => 'Hapus Pembelian #' . $pembelian->id_pembelian,
                    ]);
                }
            }

            // 2. Hapus Pengeluaran Terkait
            Pengeluaran::where('no_referensi', 'BYR-BELI-' . $pembelian->id_pembelian)->delete();

            // 3. Hapus Data
            $pembelian->delete();

            DB::commit();

            return redirect()->route('owner.pembelian.index')->with('success', 'Data pembelian dihapus dan stok dikembalikan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal hapus: ' . $e->getMessage());
        }
    }

    // --- Helper Functions ---

    private function validateRequest($request)
    {
        $request->validate([
            'id_distributor'     => 'required|exists:distributor,id_distributor',
            'no_faktur_supplier' => 'required|string|max:50',
            'tgl_pembelian'      => 'required|date',
            'status_bayar'       => 'required|in:Lunas,Hutang,Sebagian',
            'tgl_jatuh_tempo'    => 'required_if:status_bayar,Hutang,Sebagian|date|nullable',
            'nominal_bayar'      => 'nullable|numeric|min:0',
            'produk_id'          => 'required|array|min:1',
            'produk_id.*'        => 'required|exists:produk,id_produk',
            'qty'                => 'required|array',
            'qty.*'              => 'required|integer|min:1',
            'harga_beli'         => 'required|array',
            'harga_beli.*'       => 'required|numeric|min:0',
            'tgl_expired'        => 'nullable|array',
            'tgl_expired.*'      => 'nullable|date',
        ]);
    }

    private function processDetailsAndStock($pembelian, $request, $id_toko, $mode = 'Baru')
    {
        foreach ($request->produk_id as $index => $id_produk) {
            $qty      = $request->qty[$index];
            $harga    = $request->harga_beli[$index];
            $subtotal = $qty * $harga;
            $expired  = ! empty($request->tgl_expired[$index]) ? $request->tgl_expired[$index] : null;

            $produk     = Produk::find($id_produk);
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

            $produk->update(['harga_beli_rata_rata' => $harga]);

            // PERBAIKAN: Gunakan stok_fisik saat firstOrCreate dan increment
            $stokToko = StokToko::firstOrCreate(
                ['id_toko' => $id_toko, 'id_produk' => $id_produk],
                ['stok_fisik' => 0]// Default value kolom yang benar
            );

            $stokAwal = $stokToko->stok_fisik;        // Ambil nilai yang benar
            $stokToko->increment('stok_fisik', $qty); // Increment kolom yang benar

            LogStok::create([
                'id_toko'         => $id_toko,
                'id_produk'       => $id_produk,
                'id_user'         => Auth::id(),
                'jenis_transaksi' => 'Pembelian',
                'no_referensi'    => $pembelian->no_faktur_supplier,
                'qty_masuk'       => $qty,
                'qty_keluar'      => 0,
                'stok_akhir'      => $stokAwal + $qty,
                'keterangan'      => ($mode == 'Revisi' ? 'Revisi ' : '') . 'Faktur Pembelian #' . $pembelian->id_pembelian,
            ]);
        }
    }

    private function processKeuangan($pembelian, $request, $id_toko, $grandTotal)
    {
        $nominalBayar = str_replace(['.', ','], '', $request->input('nominal_bayar', 0));

        if ($request->status_bayar == 'Lunas') {
            $nominalBayar = $grandTotal;
        }

        if (($request->status_bayar == 'Lunas' || $request->status_bayar == 'Sebagian') && $nominalBayar > 0) {
            Pengeluaran::create([
                'id_toko'         => $id_toko,
                'id_user'         => Auth::id(),
                'no_referensi'    => 'BYR-BELI-' . $pembelian->id_pembelian,
                'tgl_pengeluaran' => $request->tgl_pembelian,
                'kategori_biaya'  => 'Pembelian Stok',
                'nominal'         => $nominalBayar,
                'keterangan'      => 'Pembayaran Faktur: ' . $request->no_faktur_supplier . ' (' . $request->status_bayar . ')',
            ]);
        }
    }
}
