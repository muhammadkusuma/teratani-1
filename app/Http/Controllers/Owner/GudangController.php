<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Gudang;
use App\Models\Toko;
use App\Models\StokGudang;
use Illuminate\Http\Request;

class GudangController extends Controller
{
    public function index($id_toko)
    {
        $toko = Toko::findOrFail($id_toko);
        $gudangs = Gudang::where('id_toko', $id_toko)->withCount('stokGudangs')->get();
        
        return view('owner.gudang.index', compact('toko', 'gudangs'));
    }

    public function create($id_toko)
    {
        $toko = Toko::findOrFail($id_toko);
        return view('owner.gudang.create', compact('toko'));
    }

    public function store(Request $request, $id_toko)
    {
        $toko = Toko::findOrFail($id_toko);
        
        $request->validate([
            'nama_gudang' => 'required|string|max:255',
            'lokasi'      => 'nullable|string|max:255',
        ]);

        Gudang::create([
            'nama_gudang' => $request->nama_gudang,
            'lokasi'      => $request->lokasi,
            'id_toko'     => $id_toko,
            'id_perusahaan' => auth()->user()->id_perusahaan,
        ]);

        return redirect()->route('owner.toko.gudang.index', $id_toko)->with('success', 'Gudang berhasil ditambahkan');
    }

    public function edit($id_toko, $id_gudang)
    {
        $toko = Toko::findOrFail($id_toko);
        $gudang = Gudang::where('id_toko', $id_toko)->findOrFail($id_gudang);
        
        return view('owner.gudang.edit', compact('toko', 'gudang'));
    }

    public function update(Request $request, $id_toko, $id_gudang)
    {
        $toko = Toko::findOrFail($id_toko);
        $gudang = Gudang::where('id_toko', $id_toko)->findOrFail($id_gudang);
        
        $request->validate([
            'nama_gudang' => 'required|string|max:255',
            'lokasi'      => 'nullable|string|max:255',
        ]);

        $gudang->update([
            'nama_gudang' => $request->nama_gudang,
            'lokasi'      => $request->lokasi,
        ]);

        return redirect()->route('owner.toko.gudang.index', $id_toko)->with('success', 'Gudang berhasil diperbarui');
    }

    public function destroy($id_toko, $id_gudang)
    {
        $toko = Toko::findOrFail($id_toko);
        $gudang = Gudang::where('id_toko', $id_toko)->findOrFail($id_gudang);
        $gudang->delete();

        return redirect()->route('owner.toko.gudang.index', $id_toko)->with('success', 'Gudang berhasil dihapus');
    }

    public function show($id_toko, $id_gudang)
    {
        $toko = Toko::findOrFail($id_toko);
        $gudang = Gudang::where('id_toko', $id_toko)->findOrFail($id_gudang);
        
        $stoks = StokGudang::with(['produk' => function($q) {
            $q->with('satuanKecil');
        }])
        ->where('id_gudang', $id_gudang)
        ->orderBy('stok_fisik', 'asc')
        ->paginate(20);

        return view('owner.gudang.show', compact('toko', 'gudang', 'stoks'));
    }
}
