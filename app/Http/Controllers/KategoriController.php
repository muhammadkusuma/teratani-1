<?php
namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    // ... method index, store biasa, dll ...

    /**
     * Method khusus untuk AJAX Request
     */
    public function storeAjax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required|string|max:255',
            'toko_id'       => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        try {
            $kategori = Kategori::create([
                'nama_kategori' => $request->nama_kategori,
                // PERBAIKAN DI SINI: Mapping 'toko_id' dari request ke 'id_tenant' di database
                'id_tenant'     => $request->toko_id,
            ]);

            return response()->json(['success' => true, 'data' => $kategori]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan: ' . $e->getMessage()], 500);
        }
    }
}
