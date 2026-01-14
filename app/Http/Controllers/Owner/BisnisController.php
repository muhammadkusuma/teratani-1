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

            $tenant = Tenant::create([
                'nama_bisnis'         => $request->nama_bisnis,
                'alamat_kantor_pusat' => $request->alamat,
                'owner_contact'       => $request->no_telp,
            ]);

            DB::table('user_tenant_mapping')->insert([
                'id_user'        => $user->id_user,
                'id_tenant'      => $tenant->id_tenant,
                'role_in_tenant' => 'OWNER',
                'is_primary'     => true,
            ]);
        });

        return redirect()->route('owner.dashboard')
            ->with('success', 'Selamat! Bisnis Anda berhasil dibuat. Silakan buat Toko pertama Anda.');
    }
}
