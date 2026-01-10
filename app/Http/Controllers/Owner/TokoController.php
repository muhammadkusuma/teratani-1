<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TokoController extends Controller
{
    // Menampilkan daftar toko milik Tenant user saat ini
    public function index()
    {
        $user = Auth::user();
        $tenant = $user->tenants->first(); // Asumsi 1 user 1 tenant utama
        
        $toko = Toko::where('id_tenant', $tenant->id_tenant)->get();

        return view('owner.toko.index', compact('toko'));
    }

    public function create()
    {
        return view('owner.toko.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_toko' => 'required|string|max:100',
            'kota' => 'nullable|string|max:50',
            'no_telp' => 'nullable|string|max:20',
        ]);

        $user = Auth::user();
        $tenant = $user->tenants->first();

        DB::transaction(function() use ($request, $user, $tenant) {
            // 1. Buat Toko
            $toko = Toko::create([
                'id_tenant' => $tenant->id_tenant,
                'nama_toko' => $request->nama_toko,
                'kode_toko' => 'TK-' . time(), // Contoh generate kode
                'alamat' => $request->alamat,
                'kota' => $request->kota,
                'no_telp' => $request->no_telp,
                'is_pusat' => false, // Default cabang
                'is_active' => true
            ]);

            // 2. Beri akses user owner ke toko ini (table user_toko_access)
            DB::table('user_toko_access')->insert([
                'id_user' => $user->id_user,
                'id_toko' => $toko->id_toko
            ]);
        });

        return redirect()->route('owner.toko.index')->with('success', 'Toko cabang berhasil dibuat');
    }

    // FUNGSI PENTING: Memilih Toko Aktif
    public function select(Request $request, $id_toko)
    {
        $toko = Toko::findOrFail($id_toko);
        
        // Validasi apakah toko ini milik tenant user yang login
        $user = Auth::user();
        $tenant = $user->tenants->first();

        if($toko->id_tenant !== $tenant->id_tenant) {
            abort(403, 'Akses ditolak');
        }

        // Simpan ID toko ke dalam SESSION
        session([
            'toko_active_id' => $toko->id_toko,
            'toko_active_nama' => $toko->nama_toko
        ]);

        return redirect()->route('owner.dashboard')->with('success', 'Berhasil beralih ke toko: ' . $toko->nama_toko);
    }
}