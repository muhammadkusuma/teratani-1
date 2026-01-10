<?php
namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelangganController extends Controller
{
    /**
     * Helper untuk mendapatkan ID Tenant dari User yang login.
     * Mengambil dari relasi tenants() karena User N:N Tenant.
     */
    private function getTenantId()
    {
        $user = Auth::user();

        // 1. Cek session jika ada (opsional jika nanti ada fitur switch tenant)
        if (session()->has('id_tenant')) {
            return session('id_tenant');
        }

        // 2. Ambil tenant pertama dari relasi
        // Menggunakan properti 'tenants' (collection) yang didefinisikan di User Model
        $tenant = $user->tenants->first();

        return $tenant ? $tenant->id_tenant : null;
    }

    public function index(Request $request)
    {
        $id_tenant = $this->getTenantId();

        if (! $id_tenant) {
            // Fallback jika user baru register dan belum di-assign tenant
            return redirect()->back()->with('error', 'Akun Anda belum terhubung dengan Bisnis/Tenant manapun.');
        }

        $query = Pelanggan::where('id_tenant', $id_tenant);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_pelanggan', 'like', "%{$search}%")
                    ->orWhere('no_hp', 'like', "%{$search}%")
                    ->orWhere('kode_pelanggan', 'like', "%{$search}%");
            });
        }

        $pelanggan = $query->latest()->paginate(10);

        return view('owner.pelanggan.index', compact('pelanggan'));
    }

    public function create()
    {
        return view('owner.pelanggan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:100',
            'no_hp'          => 'nullable|string|max:20',
            'limit_piutang'  => 'required|numeric|min:0',
        ]);

        $id_tenant = $this->getTenantId();

        if (! $id_tenant) {
            return back()->with('error', 'Gagal menyimpan: Tenant tidak ditemukan.');
        }

        // Generate Kode Pelanggan Otomatis
        $count = Pelanggan::where('id_tenant', $id_tenant)->count();
        $kode  = 'CUST-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);

        Pelanggan::create([
            'id_tenant'      => $id_tenant,
            'kode_pelanggan' => $request->kode_pelanggan ?? $kode,
            'nama_pelanggan' => $request->nama_pelanggan,
            'wilayah'        => $request->wilayah,
            'no_hp'          => $request->no_hp,
            'alamat'         => $request->alamat,
            'limit_piutang'  => $request->limit_piutang ?? 0,
        ]);

        return redirect()->route('owner.pelanggan.index')->with('success', 'Data Pelanggan berhasil disimpan.');
    }

    public function edit($id)
    {
        $id_tenant = $this->getTenantId();
        // Pastikan hanya mengedit data milik tenant sendiri
        $pelanggan = Pelanggan::where('id_tenant', $id_tenant)->findOrFail($id);

        return view('owner.pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:100',
            'limit_piutang'  => 'required|numeric|min:0',
        ]);

        $id_tenant = $this->getTenantId();
        $pelanggan = Pelanggan::where('id_tenant', $id_tenant)->findOrFail($id);

        $pelanggan->update([
            'nama_pelanggan' => $request->nama_pelanggan,
            'kode_pelanggan' => $request->kode_pelanggan,
            'wilayah'        => $request->wilayah,
            'no_hp'          => $request->no_hp,
            'alamat'         => $request->alamat,
            'limit_piutang'  => $request->limit_piutang,
        ]);

        return redirect()->route('owner.pelanggan.index')->with('success', 'Data Pelanggan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $id_tenant = $this->getTenantId();
        $pelanggan = Pelanggan::where('id_tenant', $id_tenant)->findOrFail($id);
        $pelanggan->delete();

        return redirect()->route('owner.pelanggan.index')->with('success', 'Data Pelanggan berhasil dihapus.');
    }
}
