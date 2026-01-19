<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Gudang;
use App\Models\StokGudang;
use Illuminate\Http\Request;

class GudangController extends Controller
{
    public function index()
    {
        $id_toko = session('toko_active_id');
        if (!$id_toko) {
             return redirect()->route('owner.dashboard')->with('error', 'Pilih Toko Terlebih Dahulu');
        }

        $gudangs = Gudang::where('id_toko', $id_toko)->withCount('stokGudangs')->get();
        return view('owner.gudang.index', compact('gudangs'));
    }

    public function show($id_gudang)
    {
        $gudang = Gudang::findOrFail($id_gudang);
        
        $stoks = StokGudang::with(['produk' => function($q) {
            $q->with('satuanKecil');
        }])
        ->where('id_gudang', $id_gudang)
        ->where('stok_fisik', '>', 0) // Optional: Show only items with stock? User requested "Sisa barang".
        ->orderBy(function($query) {
             $query->select('nama_produk')
                   ->from('produk')
                   ->whereColumn('produk.id_produk', 'stok_gudang.id_produk');
        })
        ->paginate(20);

        return view('owner.gudang.show', compact('gudang', 'stoks'));
    }
}
