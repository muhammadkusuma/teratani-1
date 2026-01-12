<?php
namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Distributor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DistributorController extends Controller
{
    // Helper untuk mendapatkan ID Tenant aktif
    private function getTenantId()
    {
        $user = Auth::user();
        if (session()->has('id_tenant')) {
            return session('id_tenant');
        }
        $tenant = $user->tenants->first();
        return $tenant ? $tenant->id_tenant : null;
    }

    public function index(Request $request)
    {
        $id_tenant = $this->getTenantId();

        if (! $id_tenant) {
            return redirect()->back()->with('error', 'Akun Anda belum terhubung dengan Bisnis/Tenant manapun.');
        }

        // Query data distributor berdasarkan tenant
        $query = Distributor::where('id_tenant', $id_tenant);

        // Fitur Pencarian
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_distributor', 'like', "%{$search}%")
                    ->orWhere('nama_kontak', 'like', "%{$search}%")
                    ->orWhere('no_telp', 'like', "%{$search}%");
            });
        }

        // Pagination 10 item per halaman
        $distributors = $query->orderBy('nama_distributor', 'asc')->paginate(10);

        return view('owner.distributor.index', compact('distributors'));
    }

    public function create()
    {
        return view('owner.distributor.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_distributor' => 'required|string|max:100',
            'nama_kontak'      => 'nullable|string|max:100',
            'no_telp'          => 'nullable|string|max:20',
            'alamat'           => 'nullable|string',
        ]);

        $id_tenant = $this->getTenantId();

        Distributor::create([
            'id_tenant'        => $id_tenant,
            'nama_distributor' => $request->nama_distributor,
            'nama_kontak'      => $request->nama_kontak,
            'no_telp'          => $request->no_telp,
            'alamat'           => $request->alamat,
        ]);

        return redirect()->route('owner.distributor.index')->with('success', 'Data Distributor berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $id_tenant = $this->getTenantId();

        // Pastikan distributor milik tenant yang sedang login
        $distributor = Distributor::where('id_tenant', $id_tenant)->findOrFail($id);

        return view('owner.distributor.edit', compact('distributor'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_distributor' => 'required|string|max:100',
            'nama_kontak'      => 'nullable|string|max:100',
            'no_telp'          => 'nullable|string|max:20',
            'alamat'           => 'nullable|string',
        ]);

        $id_tenant   = $this->getTenantId();
        $distributor = Distributor::where('id_tenant', $id_tenant)->findOrFail($id);

        $distributor->update([
            'nama_distributor' => $request->nama_distributor,
            'nama_kontak'      => $request->nama_kontak,
            'no_telp'          => $request->no_telp,
            'alamat'           => $request->alamat,
        ]);

        return redirect()->route('owner.distributor.index')->with('success', 'Data Distributor berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $id_tenant   = $this->getTenantId();
        $distributor = Distributor::where('id_tenant', $id_tenant)->findOrFail($id);

        $distributor->delete();

        return redirect()->route('owner.distributor.index')->with('success', 'Data Distributor berhasil dihapus.');
    }
}
