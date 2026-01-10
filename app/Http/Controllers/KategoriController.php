<?php
namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Toko; // Tambahkan Import Model Toko
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
        // Tambahkan validasi exists untuk memastikan toko_id valid
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required|string|max:255',
            'toko_id'       => 'required|exists:toko,id_toko', 
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        try {
            // FIX: Ambil data Toko berdasarkan ID yang dikirim dari form
            $toko = Toko::findOrFail($request->toko_id); 

            $kategori = Kategori::create([
                'nama_kategori' => $request->nama_kategori,
                // FIX: Gunakan id_tenant dari data toko, BUKAN id_toko dari request
                'id_tenant'     => $toko->id_tenant, 
            ]);

            return response()->json(['success' => true, 'data' => $kategori]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan: ' . $e->getMessage()], 500);
        }
    }
}