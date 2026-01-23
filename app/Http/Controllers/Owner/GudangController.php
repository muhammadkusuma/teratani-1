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

    public function create()
    {
        return view('owner.gudang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_gudang' => 'required|string|max:255',
            'lokasi'      => 'nullable|string|max:255',
        ]);

        $id_toko = session('toko_active_id');

        Gudang::create([
            'nama_gudang' => $request->nama_gudang,
            'lokasi'      => $request->lokasi,
            'id_toko'     => $id_toko,
            'id_perusahaan' => auth()->user()->id_perusahaan,
        ]);

        return redirect()->route('owner.gudang.index')->with('success', 'Gudang berhasil ditambahkan');
    }

    public function edit($id_gudang)
    {
        $gudang = Gudang::findOrFail($id_gudang);
        return view('owner.gudang.edit', compact('gudang'));
    }

    public function update(Request $request, $id_gudang)
    {
        $request->validate([
            'nama_gudang' => 'required|string|max:255',
            'lokasi'      => 'nullable|string|max:255',
        ]);

        $gudang = Gudang::findOrFail($id_gudang);
        $gudang->update([
            'nama_gudang' => $request->nama_gudang,
            'lokasi'      => $request->lokasi,
        ]);

        return redirect()->route('owner.gudang.index')->with('success', 'Gudang berhasil diperbarui');
    }

    public function destroy($id_gudang)
    {
        $gudang = Gudang::findOrFail($id_gudang);
        $gudang->delete();

        return redirect()->route('owner.gudang.index')->with('success', 'Gudang berhasil dihapus');
    }

    public function show($id_gudang)
    {
        $gudang = Gudang::findOrFail($id_gudang);
        
        // Remove the where stok > 0 constraint to let us show 0 stock items in red
        $stoks = StokGudang::with(['produk' => function($q) {
            $q->with('satuanKecil');
        }])
        ->where('id_gudang', $id_gudang)
        ->orderBy('stok_fisik', 'asc') // Order by lowest stock first
        ->paginate(20);

        return view('owner.gudang.show', compact('gudang', 'stoks'));
    }
}
