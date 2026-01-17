<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TokoController extends Controller
{
    public function index()
    {
        $idPerusahaan = Auth::user()->id_perusahaan;
        $toko = Toko::where('id_perusahaan', $idPerusahaan)
                    ->orderBy('is_pusat', 'desc')
                    ->orderBy('nama_toko')
                    ->paginate(20);
        return view('owner.toko.index', compact('toko'));
    }

    public function create()
    {
        return view('owner.toko.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_toko'  => 'required|unique:toko,kode_toko',
            'nama_toko'  => 'required',
            'alamat'     => 'nullable',
            'kota'       => 'nullable',
            'no_telp'    => 'nullable',
            'is_pusat'   => 'boolean',
        ]);

        

        $idPerusahaan = Auth::user()->id_perusahaan;

        Toko::create([
            'id_perusahaan'  => $idPerusahaan,
            'kode_toko'      => $request->kode_toko,
            'nama_toko'      => $request->nama_toko,
            'alamat'         => $request->alamat,
            'kota'           => $request->kota,
            'no_telp'        => $request->no_telp,
            'info_rekening'  => $request->info_rekening,
            'is_pusat'       => $request->has('is_pusat'),
            'is_active'      => true,
        ]);

        return redirect()->route('owner.toko.index')->with('success', 'Toko berhasil ditambahkan');
    }

    public function edit($id)
    {
        $toko = Toko::findOrFail($id);
        return view('owner.toko.edit', compact('toko'));
    }

    public function update(Request $request, $id)
    {
        $toko = Toko::findOrFail($id);

        $request->validate([
            'kode_toko'  => 'required|unique:toko,kode_toko,' . $id . ',id_toko',
            'nama_toko'  => 'required',
        ]);

        

        $toko->update([
            'kode_toko'      => $request->kode_toko,
            'nama_toko'      => $request->nama_toko,
            'alamat'         => $request->alamat,
            'kota'           => $request->kota,
            'no_telp'        => $request->no_telp,
            'info_rekening'  => $request->info_rekening,
            'is_pusat'       => $request->has('is_pusat'),
            'is_active'      => $request->has('is_active'),
        ]);

        return redirect()->route('owner.toko.index')->with('success', 'Toko berhasil diupdate');
    }

    public function destroy($id)
    {
        $toko = Toko::findOrFail($id);

        if ($toko->is_pusat) {
            return redirect()->back()->with('error', 'Toko pusat tidak bisa dihapus');
        }

        $toko->delete();
        return redirect()->route('owner.toko.index')->with('success', 'Toko berhasil dihapus');
    }

    public function select($id)
    {
        $toko = Toko::findOrFail($id);

        session([
            'toko_active_id'   => $toko->id_toko,
            'toko_active_nama' => $toko->nama_toko,
        ]);

        return redirect()->route('owner.dashboard')->with('success', 'Toko ' . $toko->nama_toko . ' berhasil dipilih');
    }
}
