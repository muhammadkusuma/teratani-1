<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Distributor;
use App\Models\LogStok;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
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
        
        // Data Pembelian tetap per Toko (Transactional)
        $pembelians = Pembelian::with(['distributor', 'user'])
            ->where('id_toko', $id_toko)
            ->orderBy('tgl_pembelian', 'desc')
            ->paginate(10);

        return view('owner.pembelian.index', compact('pembelians'));
    }

    public function create()
    {
        $id_toko = session('toko_active_id');
        
        // 1. Ambil Data Toko Aktif untuk mengetahui Tenant-nya
        $toko = Toko::findOrFail($id_toko);
        $id_tenant = $toko->id_tenant;

        // 2. Ambil Master Data berdasarkan TENANT (bukan id_toko)
        $distributors = Distributor::where('id_tenant', $id_tenant)
            ->orderBy('nama_distributor', 'asc')
            ->get();
            
        $produks = Produk::where('id_tenant', $id_tenant)
            ->where('is_active', true)
            ->with(['satuanKecil', 'satuanBesar']) // Eager load satuan jika relasi ada
            ->orderBy('nama_produk', 'asc')
            ->get();

        return view('owner.pembelian.create', compact('distributors', 'produks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_distributor' => 'required|exists:distributor,id_distributor',
            'no_faktur_supplier' => 'required|string|max:50',
            'tgl_pembelian' => 'required|date',
            'tgl_jatuh_tempo' => 'nullable|date',
            'status_bayar' => 'required|in:Lunas,Hutang,Sebagian',
            'produk_id' => 'required|array',
            'produk_id.*' => 'required|exists:produk,id_produk',
            'qty' => 'required|array',
            'qty.*' => 'required|integer|min:1',
            'harga_beli' => 'required|array',
            'harga_beli.*' => 'required|numeric|min:0',
        ]);

        $id_toko = session('toko_active_id');

        try {
            DB::beginTransaction();

            // 1. Simpan Header Pembelian (Transactional -> Per Toko)
            $pembelian = Pembelian::create([
                'id_toko' => $id_toko,
                'id_distributor' => $request->id_distributor,
                'id_user' => Auth::id(),
                'no_faktur_supplier' => $request->no_faktur_supplier,
                'tgl_pembelian' => $request->tgl_pembelian,
                'tgl_jatuh_tempo' => $request->tgl_jatuh_tempo,
                'status_bayar' => $request->status_bayar,
                'keterangan' => $request->keterangan,
                'total_pembelian' => 0 // Update nanti
            ]);

            $grandTotal = 0;

            // 2. Simpan Detail & Update Stok
            foreach ($request->produk_id as $index => $id_produk) {
                $qty = $request->qty[$index];
                $harga = $request->harga_beli[$index];
                $subtotal = $qty * $harga;
                $expired = $request->tgl_expired[$index] ?? null;

                // Ambil data produk untuk mendapatkan nama satuan
                $produk = Produk::find($id_produk);
                
                // Cari nama satuan kecil (Manual lookup agar aman jika relasi belum dibuat)
                $namaSatuan = 'Pcs';
                if ($produk->id_satuan_kecil) {
                    $satuanObj = Satuan::find($produk->id_satuan_kecil);
                    if ($satuanObj) $namaSatuan = $satuanObj->nama_satuan;
                }

                // Simpan Detail
                PembelianDetail::create([
                    'id_pembelian' => $pembelian->id_pembelian,
                    'id_produk' => $id_produk,
                    'qty' => $qty,
                    'satuan_beli' => $namaSatuan, 
                    'harga_beli_satuan' => $harga,
                    'subtotal' => $subtotal,
                    'tgl_expired_item' => $expired
                ]);

                $grandTotal += $subtotal;

                // Update Harga Beli Terakhir di Master Produk (Opsional, agar data master update)
                // Kita update harga_beli_rata_rata atau buat field baru harga_beli_terakhir
                // Di sini saya update harga_beli_rata_rata sementara
                $produk->update(['harga_beli_rata_rata' => $harga]);

                // 3. Update Stok Toko
                $stokToko = StokToko::firstOrCreate(
                    ['id_toko' => $id_toko, 'id_produk' => $id_produk],
                    ['stok' => 0]
                );
                
                $stokAwal = $stokToko->stok;
                $stokToko->increment('stok', $qty);

                // 4. Catat Log Stok
                LogStok::create([
                    'id_toko' => $id_toko,
                    'id_produk' => $id_produk,
                    'id_user' => Auth::id(),
                    'jenis_transaksi' => 'Pembelian',
                    'no_referensi' => $pembelian->no_faktur_supplier,
                    'qty_masuk' => $qty,
                    'qty_keluar' => 0,
                    'stok_akhir' => $stokAwal + $qty,
                    'keterangan' => 'Pembelian dari Distributor ID: ' . $request->id_distributor
                ]);
            }

            // Update Total
            $pembelian->update(['total_pembelian' => $grandTotal]);

            DB::commit();

            return redirect()->route('owner.pembelian.index')
                ->with('success', 'Faktur Pembelian berhasil disimpan & Stok bertambah.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $pembelian = Pembelian::with(['details.produk', 'distributor'])
            ->where('id_toko', session('toko_active_id'))
            ->findOrFail($id);

        return view('owner.pembelian.show', compact('pembelian'));
    }
}