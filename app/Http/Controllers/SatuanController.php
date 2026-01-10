<?php
namespace App\Http\Controllers;

use App\Models\Satuan;
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
            'toko_id'     => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        try {
            $satuan = Satuan::create([
                'nama_satuan' => $request->nama_satuan,
                // PERBAIKAN DI SINI: Mapping 'toko_id' dari request ke 'id_tenant' di database
                'id_tenant'   => $request->toko_id,
            ]);

            return response()->json(['success' => true, 'data' => $satuan]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan: ' . $e->getMessage()], 500);
        }
    }
}
