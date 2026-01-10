<?php
namespace App\Http\Controllers;

use App\Models\Satuan;
use App\Models\Toko; // 1. Tambahkan Import Model Toko
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SatuanController extends Controller
{
    // ... method index, store biasa, dll ...

    /**
     * Method khusus untuk AJAX Request
     */
    public function storeAjax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_satuan' => 'required|string|max:255',
            // 2. Validasi bahwa toko_id benar-benar ada di tabel toko
            'toko_id'     => 'required|exists:toko,id_toko', 
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        try {
            // 3. Ambil data Toko berdasarkan ID yang dikirim
            $toko = Toko::findOrFail($request->toko_id);

            $satuan = Satuan::create([
                'nama_satuan' => $request->nama_satuan,
                // 4. PERBAIKAN: Gunakan id_tenant dari data toko, BUKAN id_toko dari request
                'id_tenant'   => $toko->id_tenant, 
            ]);

            return response()->json(['success' => true, 'data' => $satuan]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan: ' . $e->getMessage()], 500);
        }
    }
}