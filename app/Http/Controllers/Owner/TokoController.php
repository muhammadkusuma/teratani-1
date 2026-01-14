<?php
namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TokoController extends Controller
{
    public function index()
    {
        $user   = Auth::user();
        $tenant = $user->tenants->first();

        if (! $tenant) {
            return view('owner.toko.index', ['toko' => collect([])]);
        }

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
            'nama_toko'     => 'required|string|max:100',
            'alamat'        => 'nullable|string',
            'kota'          => 'nullable|string|max:50',
            'no_telp'       => 'nullable|string|max:20',
            'info_rekening' => 'nullable|string',
        ]);

        $user   = Auth::user();
        $tenant = $user->tenants->first();

        if (! $tenant) {
            return redirect()->back()->with('error', 'Anda belum memiliki bisnis (Tenant). Silakan buat terlebih dahulu.');
        }

        DB::transaction(function () use ($request, $user, $tenant) {
            $toko = Toko::create([
                'id_tenant'     => $tenant->id_tenant,
                'nama_toko'     => $request->nama_toko,
                'kode_toko'     => 'TK-' . time(),
                'alamat'        => $request->alamat,
                'kota'          => $request->kota,
                'no_telp'       => $request->no_telp,
                'info_rekening' => $request->info_rekening,
                'is_pusat'      => false,
                'is_active'     => true,
            ]);

        });


        return redirect()->route('owner.toko.index')->with('success', 'Toko cabang berhasil dibuat');
    }

    public function edit($id_toko)
    {
        $toko = Toko::findOrFail($id_toko);

        $user   = Auth::user();
        $tenant = $user->tenants->first();

        if (! $tenant) {
            abort(403, 'Akses ditolak: User tidak memiliki tenant.');
        }

        if ($toko->id_tenant !== $tenant->id_tenant) {
            abort(403, 'Akses ditolak');
        }

        return view('owner.toko.edit', compact('toko'));
    }

    public function update(Request $request, $id_toko)
    {
        $request->validate([
            'nama_toko' => 'required|string|max:100',
            'alamat'    => 'nullable|string',
            'kota'      => 'nullable|string|max:50',
            'no_telp'   => 'nullable|string|max:20',
            'info_rekening' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $toko = Toko::findOrFail($id_toko);

        $user   = Auth::user();
        $tenant = $user->tenants->first();

        if (! $tenant) {
            abort(403, 'Akses ditolak: User tidak memiliki tenant.');
        }

        if ($toko->id_tenant !== $tenant->id_tenant) {
            abort(403, 'Akses ditolak');
        }

        $toko->update([
            'nama_toko' => $request->nama_toko,
            'alamat'    => $request->alamat,
            'kota'      => $request->kota,
            'no_telp'   => $request->no_telp,
            'info_rekening' => $request->info_rekening,
            'is_active' => $request->has('is_active') ? $request->is_active : $toko->is_active,
        ]);

        return redirect()->route('owner.toko.index')->with('success', 'Data toko berhasil diperbarui');
    }

    public function destroy($id_toko)
    {
        $toko = Toko::findOrFail($id_toko);

        $user   = Auth::user();
        $tenant = $user->tenants->first();

        if (! $tenant) {
            abort(403, 'Akses ditolak: User tidak memiliki tenant.');
        }

        if ($toko->id_tenant !== $tenant->id_tenant) {
            abort(403, 'Akses ditolak');
        }

        if ($toko->is_pusat) {
            return redirect()->back()->with('error', 'Toko Pusat tidak dapat dihapus.');
        }

        $toko->delete();

        return redirect()->route('owner.toko.index')->with('success', 'Toko berhasil dihapus');
    }

    public function select(Request $request, $id_toko)
    {
        $toko = Toko::findOrFail($id_toko);

        $user   = Auth::user();
        $tenant = $user->tenants->first();

        if (! $tenant) {
            abort(403, 'Akses ditolak: User tidak memiliki tenant.');
        }

        if ($toko->id_tenant !== $tenant->id_tenant) {
            abort(403, 'Akses ditolak');
        }

        session([
            'toko_active_id'   => $toko->id_toko,
            'toko_active_nama' => $toko->nama_toko,
        ]);

        return redirect()->route('owner.dashboard')->with('success', 'Berhasil beralih ke toko: ' . $toko->nama_toko);
    }
}
