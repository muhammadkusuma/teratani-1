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

        if ($request->has('search')) {
            $search = $request->search;
            // Sesuaikan pencarian dengan nama kolom di DB
            $query->where('nama_bisnis', 'like', "%{$search}%")
                ->orWhere('kode_unik_tenant', 'like', "%{$search}%");
        }

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
            // Validasi input form
            'nama_tenant' => 'required|string|max:100',
            // Validasi input domain ke kolom DB 'kode_unik_tenant'
            'domain'      => 'nullable|string|max:20|unique:tenants,kode_unik_tenant',
            'status'      => 'required|in:active,inactive',
            // id_tenant dihapus karena DB menggunakan Auto Increment
        ]);

        Tenant::create([
            // Kiri: Kolom Database => Kanan: Input Form
            'nama_bisnis'      => $request->nama_tenant,
            'kode_unik_tenant' => $request->domain, // Mapping domain form ke kode_unik DB
            'status_langganan' => $request->status === 'active' ? 'Aktif' : 'Suspend',
            'paket_layanan'    => 'Trial', // Default value
        ]);

        return redirect()->route('tenants.index')->with('success', 'Tenant berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit tenant.
     */
    public function edit($id)
    {
        $tenant = Tenant::findOrFail($id);
        return view('tenants.edit', compact('tenant'));
    }

    /**
     * Memperbarui data tenant.
     */
    public function update(Request $request, $id)
    {
        $tenant = Tenant::findOrFail($id);

        $request->validate([
            'nama_tenant' => 'required|string|max:100',
            // Cek unique kecuali punya diri sendiri
            'domain'      => ['nullable', 'string', 'max:20', Rule::unique('tenants', 'kode_unik_tenant')->ignore($tenant->id_tenant, 'id_tenant')],
            'status'      => 'required|in:active,inactive',
        ]);

        $tenant->update([
            'nama_bisnis'      => $request->nama_tenant,
            'kode_unik_tenant' => $request->domain,
            'status_langganan' => $request->status === 'active' ? 'Aktif' : 'Suspend',
        ]);

        return redirect()->route('tenants.index')->with('success', 'Data tenant berhasil diperbarui.');
    }

    /**
     * Menghapus tenant.
     */
    public function destroy($id)
    {
        $tenant = Tenant::findOrFail($id);
        $tenant->delete();

        return redirect()->route('tenants.index')->with('success', 'Tenant berhasil dihapus.');
    }
}
