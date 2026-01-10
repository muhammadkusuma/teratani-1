<?php
namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TenantController extends Controller
{
    /**
     * Menampilkan daftar tenant.
     */
    public function index(Request $request)
    {
        $query = Tenant::query();

        // Fitur pencarian
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nama_tenant', 'like', "%{$search}%")
                ->orWhere('domain', 'like', "%{$search}%");
        }

        // Pagination
        $tenants = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('tenants.index', compact('tenants'));
    }

    /**
     * Menampilkan form tambah tenant.
     */
    public function create()
    {
        return view('tenants.create');
    }

    /**
     * Menyimpan tenant baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_tenant'   => 'required|alpha_dash|unique:tenants,id_tenant|max:50', // ID manual (misal: "toko-budi")
            'nama_tenant' => 'required|string|max:100',
            'domain'      => 'nullable|string|max:100|unique:tenants,domain',
            'status'      => 'required|in:active,inactive',
        ]);

        Tenant::create([
            'id_tenant'   => $request->id_tenant,
            'nama_tenant' => $request->nama_tenant,
            'domain'      => $request->domain,
            'status'      => $request->status,
        ]);

        return redirect()->route('tenants.index')->with('success', 'Tenant berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit tenant.
     */
    public function edit($id)
    {
        // Menggunakan findOrFail karena id_tenant mungkin string/non-increment
        $tenant = Tenant::where('id_tenant', $id)->firstOrFail();
        return view('tenants.edit', compact('tenant'));
    }

    /**
     * Memperbarui data tenant.
     */
    public function update(Request $request, $id)
    {
        $tenant = Tenant::where('id_tenant', $id)->firstOrFail();

        $request->validate([
            // ID Tenant biasanya tidak boleh diubah karena berelasi dengan banyak tabel
            'nama_tenant' => 'required|string|max:100',
            'domain'      => ['nullable', 'string', 'max:100', Rule::unique('tenants')->ignore($tenant->id_tenant, 'id_tenant')],
            'status'      => 'required|in:active,inactive',
        ]);

        $tenant->update([
            'nama_tenant' => $request->nama_tenant,
            'domain'      => $request->domain,
            'status'      => $request->status,
        ]);

        return redirect()->route('tenants.index')->with('success', 'Data tenant berhasil diperbarui.');
    }

    /**
     * Menghapus tenant.
     */
    public function destroy($id)
    {
        $tenant = Tenant::where('id_tenant', $id)->firstOrFail();

        // Opsional: Cek apakah ada data penting sebelum hapus
        $tenant->delete();

        return redirect()->route('tenants.index')->with('success', 'Tenant berhasil dihapus.');
    }
}
