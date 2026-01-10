<?php
namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BisnisController extends Controller
{
    public function create()
    {
        if (Auth::user()->tenants()->exists()) {
            return redirect()->route('owner.dashboard');
        }

        return view('owner.bisnis.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bisnis' => 'required|string|max:100',
            'alamat'      => 'nullable|string',
            'no_telp'     => 'nullable|string|max:20',
        ]);

        DB::transaction(function () use ($request) {
            $user = Auth::user();

            // 1. Buat Data Tenant
            // PERBAIKAN: Sesuaikan key array dengan nama kolom di database (migration)
            $tenant = Tenant::create([
                'nama_bisnis'         => $request->nama_bisnis, // Sesuai migration: nama_bisnis
                'alamat_kantor_pusat' => $request->alamat,      // Sesuai migration: alamat_kantor_pusat
                'owner_contact'       => $request->no_telp,     // Sesuai migration: owner_contact
                                                                // Kolom lain seperti paket_layanan, max_toko menggunakan default value dari database
            ]);

            // 2. Hubungkan User dengan Tenant (Pivot Table)
            DB::table('user_tenant_mapping')->insert([
                'id_user'        => $user->id_user,
                'id_tenant'      => $tenant->id_tenant,
                'role_in_tenant' => 'OWNER', // Tambahkan role jika di migration kolom ini tidak nullable/default
                'is_primary'     => true,
            ]);
        });

        return redirect()->route('owner.dashboard')
            ->with('success', 'Selamat! Bisnis Anda berhasil dibuat. Silakan buat Toko pertama Anda.');
    }
}
