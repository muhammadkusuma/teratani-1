<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\ReturPembelian;
use App\Models\ReturPembelianDetail;
use App\Models\Produk;
use App\Models\StokGudang;
use App\Models\Gudang;
use App\Models\Distributor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturPembelianController extends Controller
{
    public function index()
    {
        $id_toko = session('toko_active_id'); // Filter by company ideally, but context is shop
        // Assuming Owner sees all for their company or filtered by shop context if tied
        // For now, let's assume we show all returns for the company or filtered by warehouses in this shop
        
        $returs = ReturPembelian::with(['distributor', 'gudang'])
            ->latest()
            ->paginate(15);

        return view('owner.retur-pembelian.index', compact('returs'));
    }

    public function create()
    {
        $id_toko = session('toko_active_id');
        $gudangs = Gudang::where('id_toko', $id_toko)->get();
        $distributors = Distributor::all(); // Or filtered by company
        $produks = Produk::all(); 

        return view('owner.retur-pembelian.create', compact('gudangs', 'distributors', 'produks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_distributor' => 'required|exists:distributor,id_distributor',
            'id_gudang' => 'required|exists:gudang,id_gudang',
            'tgl_retur' => 'required|date',
            'produk_id' => 'required|array',
            'qty' => 'required|array',
            'harga_satuan' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            $retur = ReturPembelian::create([
                'id_distributor' => $request->id_distributor,
                'id_gudang' => $request->id_gudang,
                'tgl_retur' => $request->tgl_retur,
                'keterangan' => $request->keterangan,
            ]);

            $totalRetur = 0;

            foreach ($request->produk_id as $key => $id_produk) {
                $qty = $request->qty[$key];
                $harga = $request->harga_satuan[$key];
                $subtotal = $qty * $harga;

                if ($qty > 0) {
                    ReturPembelianDetail::create([
                        'id_retur_pembelian' => $retur->id_retur_pembelian,
                        'id_produk' => $id_produk,
                        'qty' => $qty,
                        'harga_satuan' => $harga,
                        'subtotal' => $subtotal,
                    ]);

                    $totalRetur += $subtotal;

                    // Decrease Stock in StokGudang
                    $stokGudang = StokGudang::where('id_gudang', $request->id_gudang)
                                            ->where('id_produk', $id_produk)
                                            ->first();
                    
                    if ($stokGudang) {
                        if ($stokGudang->stok_fisik >= $qty) {
                            $stokGudang->decrement('stok_fisik', $qty);
                        } else {
                            throw new \Exception("Stok tidak cukup untuk produk ID: $id_produk");
                        }
                    } else {
                         throw new \Exception("Stok tidak ditemukan di gudang ini untuk produk ID: $id_produk");
                    }
                }
            }

            $retur->update(['total_retur' => $totalRetur]);

            DB::commit();
            return redirect()->route('owner.retur-pembelian.index')->with('success', 'Retur Pembelian berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $retur = ReturPembelian::with(['details.produk', 'distributor', 'gudang'])->findOrFail($id);
        return view('owner.retur-pembelian.show', compact('retur'));
    }
}
