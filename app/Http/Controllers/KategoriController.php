<?php
namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    public function index()
    {
        $idToko = session('toko_active_id');
        if (!$idToko) {
            return redirect()->route('owner.toko.index')->with('error', 'Silakan pilih toko terlebih dahulu.');
        }

        $toko = Toko::findOrFail($idToko);
        
        $kategori = Kategori::where('id_tenant', $toko->id_tenant)
            ->orderBy('nama_kategori', 'asc')
            ->get();

        return view('owner.kategori.index', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:50',
        ]);

        $idToko = session('toko_active_id');
        $toko = Toko::findOrFail($idToko);

        Kategori::create([
            'nama_kategori' => $request->nama_kategori,
            'id_tenant'     => $toko->id_tenant,
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:50',
        ]);

        $kategori = Kategori::findOrFail($id);
        $kategori->update([
            'nama_kategori' => $request->nama_kategori
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);
        

        $kategori->delete();

        return redirect()->back()->with('success', 'Kategori berhasil dihapus.');
    }

    


    public function storeAjax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required|string|max:255',
            'toko_id'       => 'required|exists:toko,id_toko', 
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        try {
            $toko = Toko::findOrFail($request->toko_id); 
            $kategori = Kategori::create([
                'nama_kategori' => $request->nama_kategori,
                'id_tenant'     => $toko->id_tenant, 
            ]);

            return response()->json(['success' => true, 'data' => $kategori]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan: ' . $e->getMessage()], 500);
        }
    }
}