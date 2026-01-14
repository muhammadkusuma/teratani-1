<?php
namespace App\Http\Controllers;

use App\Models\Satuan;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SatuanController extends Controller
{
    public function index()
    {
        $idToko = session('toko_active_id');
        if (! $idToko) {
            return redirect()->route('owner.toko.index')->with('error', 'Silakan pilih toko terlebih dahulu.');
        }

        $toko = Toko::findOrFail($idToko);

        $satuan = Satuan::where('id_tenant', $toko->id_tenant)
            ->orderBy('nama_satuan', 'asc')
            ->get();

        return view('owner.satuan.index', compact('satuan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_satuan' => 'required|string|max:20',
        ]);

        $idToko = session('toko_active_id');
        $toko   = Toko::findOrFail($idToko);

        Satuan::create([
            'nama_satuan' => $request->nama_satuan,
            'id_tenant'   => $toko->id_tenant,
        ]);

        return redirect()->back()->with('success', 'Satuan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_satuan' => 'required|string|max:20',
        ]);

        $satuan = Satuan::findOrFail($id);
        $satuan->update([
            'nama_satuan' => $request->nama_satuan,
        ]);

        return redirect()->back()->with('success', 'Satuan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $satuan = Satuan::findOrFail($id);
        $satuan->delete();

        return redirect()->back()->with('success', 'Satuan berhasil dihapus.');
    }

    /**
     * Method khusus untuk AJAX Request (Code Lama Anda)
     */
    public function storeAjax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_satuan' => 'required|string|max:255',
            'toko_id'     => 'required|exists:toko,id_toko',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        try {
            $toko   = Toko::findOrFail($request->toko_id);
            $satuan = Satuan::create([
                'nama_satuan' => $request->nama_satuan,
                'id_tenant'   => $toko->id_tenant,
            ]);

            return response()->json(['success' => true, 'data' => $satuan]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan: ' . $e->getMessage()], 500);
        }
    }
}
