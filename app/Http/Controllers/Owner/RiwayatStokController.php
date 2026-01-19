<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Gudang;
use App\Models\RiwayatStok;
use App\Models\Toko;
use Illuminate\Http\Request;

class RiwayatStokController extends Controller
{
    public function index(Request $request)
    {
        $id_toko = session('toko_active_id');
        if (!$id_toko) {
            return redirect()->back()->with('error', 'Pilih Toko Terlebih Dahulu');
        }

        $query = RiwayatStok::with(['produk', 'gudang', 'toko'])
            ->where(function($q) use ($id_toko) {
                // Show logs for this Store OR Warehouses of this Store
                $q->where('id_toko', $id_toko)
                  ->orWhereHas('gudang', function($g) use ($id_toko) {
                      $g->where('id_toko', $id_toko);
                  });
            })
            ->orderBy('created_at', 'desc');

        // Filter by Date
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        // Filter by Warehouse/Toko
        if ($request->has('location_type') && $request->location_type) {
            if ($request->location_type == 'toko') {
                $query->whereNotNull('id_toko');
                if ($request->has('location_id') && $request->location_id) {
                     $query->where('id_toko', $request->location_id);
                }
            } elseif ($request->location_type == 'gudang') {
                $query->whereNotNull('id_gudang');
                if ($request->has('location_id') && $request->location_id) {
                     $query->where('id_gudang', $request->location_id);
                }
            }
        }
        
        // Filter by Type (Masuk/Keluar)
        if ($request->has('jenis') && $request->jenis) {
            $query->where('jenis', $request->jenis);
        }

        $riwayats = $query->paginate(20)->withQueryString();
        
        // Get lists for filter
        $gudangs = Gudang::where('id_toko', $id_toko)->get();
        $tokos = Toko::where('id_toko', $id_toko)->get();

        return view('owner.riwayat_stok.index', compact('riwayats', 'gudangs', 'tokos'));
    }

    public function create()
    {
        $id_toko = session('toko_active_id');
        if (!$id_toko) {
            return redirect()->back()->with('error', 'Pilih Toko Terlebih Dahulu');
        }

        $gudangs = Gudang::where('id_toko', $id_toko)->get();
        $tokos = Toko::where('id_toko', $id_toko)->get();
        $produks = \App\Models\Produk::select('id_produk', 'nama_produk', 'sku')->orderBy('nama_produk')->get();
        return view('owner.riwayat_stok.create', compact('gudangs', 'tokos', 'produks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'location_type' => 'required|in:toko,gudang',
            'location_id' => 'required',
            'jenis' => 'required|in:masuk,keluar',
            'items' => 'required|array|min:1',
            'items.*.id_produk' => 'required|exists:produk,id_produk',
            'items.*.jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            foreach ($request->items as $item) {
                $stokAkhir = 0;
                
                if ($request->location_type == 'gudang') {
                    $stokGudang = \App\Models\StokGudang::firstOrCreate(
                        ['id_gudang' => $request->location_id, 'id_produk' => $item['id_produk']],
                        ['stok_fisik' => 0]
                    );
                    
                    if ($request->jenis == 'masuk') {
                        $stokGudang->increment('stok_fisik', $item['jumlah']);
                    } else {
                        $stokGudang->decrement('stok_fisik', $item['jumlah']);
                    }
                    $stokAkhir = $stokGudang->stok_fisik;
                    
                } else {
                    $stokToko = \App\Models\StokToko::firstOrCreate(
                        ['id_toko' => $request->location_id, 'id_produk' => $item['id_produk']],
                        ['stok_fisik' => 0, 'stok_minimal' => 5]
                    );
                    
                    if ($request->jenis == 'masuk') {
                        $stokToko->increment('stok_fisik', $item['jumlah']);
                    } else {
                        $stokToko->decrement('stok_fisik', $item['jumlah']);
                    }
                    $stokAkhir = $stokToko->stok_fisik;
                }

                RiwayatStok::create([
                    'id_produk' => $item['id_produk'],
                    'id_gudang' => $request->location_type == 'gudang' ? $request->location_id : null,
                    'id_toko' => $request->location_type == 'toko' ? $request->location_id : null,
                    'jenis' => $request->jenis,
                    'jumlah' => $item['jumlah'],
                    'stok_akhir' => $stokAkhir,
                    'keterangan' => $request->keterangan ?? 'Penyesuaian Manual',
                    'referensi' => 'ADJUSTMENT',
                    'tanggal' => $request->tanggal,
                ]);
            }

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('owner.riwayat-stok.index')->with('success', 'Penyesuaian stok berhasil disimpan.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
}
