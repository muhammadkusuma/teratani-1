<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    private function getActiveTokoId()
    {
        return session('toko_active_id');
    }

    public function index(Request $request)
    {
        $id_toko = $this->getActiveTokoId();

        if (!$id_toko) {
            return redirect()->route('owner.toko.index')->with('warning', 'Silakan pilih Toko/Cabang terlebih dahulu.');
        }

        $query = Pelanggan::where('id_toko', $id_toko);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_pelanggan', 'like', "%{$search}%")
                    ->orWhere('no_hp', 'like', "%{$search}%")
                    ->orWhere('kode_pelanggan', 'like', "%{$search}%");
            });
        }

        $pelanggan = $query->latest()->paginate(10);

        return view('owner.pelanggan.index', compact('pelanggan'));
    }

    public function create()
    {
        $id_toko = $this->getActiveTokoId();

        if (!$id_toko) {
            return redirect()->route('owner.toko.index')->with('warning', 'Silakan pilih Toko/Cabang terlebih dahulu.');
        }

        return view('owner.pelanggan.create');
    }

    public function store(Request $request)
    {
        $id_toko = $this->getActiveTokoId();

        if (!$id_toko) {
            return redirect()->route('owner.toko.index')->with('error', 'Toko belum dipilih');
        }

        $count = Pelanggan::where('id_toko', $id_toko)->count();
        $kode = 'PLG-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        $request->validate([
            'nama_pelanggan' => 'required',
        ]);

        Pelanggan::create([
            'id_toko'        => $id_toko,
            'kode_pelanggan' => $kode,
            'nama_pelanggan' => $request->nama_pelanggan,
            'no_hp'          => $request->no_hp,
            'alamat'         => $request->alamat,
            'wilayah'        => $request->wilayah,
            'limit_piutang'  => $request->limit_piutang ?? 0,
        ]);

        return redirect()->route('owner.pelanggan.index')->with('success', 'Pelanggan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        return view('owner.pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        $request->validate([
            'nama_pelanggan' => 'required',
        ]);

        $pelanggan->update([
            'nama_pelanggan' => $request->nama_pelanggan,
            'no_hp'          => $request->no_hp,
            'alamat'         => $request->alamat,
            'wilayah'        => $request->wilayah,
            'limit_piutang'  => $request->limit_piutang ?? 0,
        ]);

        return redirect()->route('owner.pelanggan.index')->with('success', 'Pelanggan berhasil diupdate');
    }

    public function destroy($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->delete();

        return redirect()->route('owner.pelanggan.index')->with('success', 'Pelanggan berhasil dihapus');
    }
}
