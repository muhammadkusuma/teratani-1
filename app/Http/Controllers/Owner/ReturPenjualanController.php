<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\ReturPenjualan;
use App\Models\ReturPenjualanDetail;
use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\StokToko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturPenjualanController extends Controller
{
    public function index()
    {
        $id_toko = session('toko_active_id');
        $returs = ReturPenjualan::with(['pelanggan', 'user'])
            ->where('id_toko', $id_toko)
            ->latest()
            ->paginate(15);

        // Calculate Summary Statistics
        $summaryQuery = ReturPenjualan::where('id_toko', $id_toko);
        
        $summary = [
            'total_value' => (clone $summaryQuery)->sum('total_retur'),
            'total_count' => (clone $summaryQuery)->count(),
            'today_value' => (clone $summaryQuery)->whereDate('tgl_retur', now())->sum('total_retur'),
        ];

        return view('owner.retur-penjualan.index', compact('returs', 'summary'));
    }

    public function create()
    {
        $id_toko = session('toko_active_id');
        // Fetch products for dropdown, maybe improve with AJAX search later
        $produks = Produk::whereHas('stokTokos', function($q) use ($id_toko) {
            $q->where('id_toko', $id_toko);
        })->get();
        
        $pelanggans = \App\Models\Pelanggan::where('id_toko', $id_toko)->get();

        return view('owner.retur-penjualan.create', compact('produks', 'pelanggans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pelanggan' => 'required|exists:pelanggan,id_pelanggan',
            'tgl_retur' => 'required|date',
            'produk_id' => 'required|array',
            'qty' => 'required|array',
            'harga_satuan' => 'required|array',
        ]);

        $id_toko = session('toko_active_id');

        DB::beginTransaction();
        try {
            $retur = ReturPenjualan::create([
                'id_toko' => $id_toko,
                'id_pelanggan' => $request->id_pelanggan,
                'tgl_retur' => $request->tgl_retur,
                'id_user' => auth()->id(), // Assuming user is logged in
                'keterangan' => $request->keterangan,
                'status_retur' => 'Selesai', // Auto complete for now
            ]);

            $totalRetur = 0;

            foreach ($request->produk_id as $key => $id_produk) {
                $qty = $request->qty[$key];
                $harga = $request->harga_satuan[$key];
                $subtotal = $qty * $harga;

                if ($qty > 0) {
                    ReturPenjualanDetail::create([
                        'id_retur_penjualan' => $retur->id_retur_penjualan,
                        'id_produk' => $id_produk,
                        'qty' => $qty,
                        'harga_satuan' => $harga,
                        'subtotal' => $subtotal,
                    ]);

                    $totalRetur += $subtotal;

                    // Increase Stock in StokToko
                    $stokToko = StokToko::where('id_toko', $id_toko)
                                        ->where('id_produk', $id_produk)
                                        ->first();
                    
                    if ($stokToko) {
                        $stokToko->increment('stok_fisik', $qty);
                    } else {
                        // Create if not exists (unlikely but safe)
                        StokToko::create([
                            'id_toko' => $id_toko,
                            'id_produk' => $id_produk,
                            'stok_fisik' => $qty,
                        ]);
                    }
                }
            }

            $retur->update(['total_retur' => $totalRetur]);

            DB::commit();
            return redirect()->route('owner.retur-penjualan.index')->with('success', 'Retur Penjualan berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $retur = ReturPenjualan::with(['details.produk', 'pelanggan', 'user'])->findOrFail($id);
        return view('owner.retur-penjualan.show', compact('retur'));
    }
}
