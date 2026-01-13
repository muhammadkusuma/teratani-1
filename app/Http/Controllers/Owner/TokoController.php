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
        $user   = Auth::user();
        $tenant = $user->tenants->first(); // Asumsi 1 user 1 tenant utama

        // --- FIX: Cek apakah tenant ada ---
        if (! $tenant) {
            // Jika tidak ada tenant, kembalikan collection kosong agar view tidak error
            // Atau bisa redirect ke halaman pembuatan tenant
            return view('owner.toko.index', ['toko' => collect([])]);
        }
        // ----------------------------------

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
            'alamat'        => 'nullable|string', // Tambahkan validasi alamat
            'kota'          => 'nullable|string|max:50',
            'no_telp'       => 'nullable|string|max:20',
            'info_rekening' => 'nullable|string',
        ]);

        $user   = Auth::user();
        $tenant = $user->tenants->first();

        // --- FIX: Cek tenant sebelum proses ---
        if (! $tenant) {
            return redirect()->back()->with('error', 'Anda belum memiliki bisnis (Tenant). Silakan buat terlebih dahulu.');
        }
        // --------------------------------------

        DB::transaction(function () use ($request, $user, $tenant) {
            // 1. Buat Toko
            $toko = Toko::create([
                'id_tenant'     => $tenant->id_tenant,
                'nama_toko'     => $request->nama_toko,
                'kode_toko'     => 'TK-' . time(), // Contoh generate kode
                'alamat'        => $request->alamat,
                'kota'          => $request->kota,
                'no_telp'       => $request->no_telp,
                'info_rekening' => $request->info_rekening,
                'is_pusat'      => false, // Default cabang
                'is_active'     => true,
            ]);

            // 2. Beri akses user owner ke toko ini (table user_toko_access)
            DB::table('user_toko_access')->insert([
                'id_user' => $user->id_user,
                'id_toko' => $toko->id_toko,
            ]);
        });

        return redirect()->route('owner.toko.index')->with('success', 'Toko cabang berhasil dibuat');
    }

    // --- FUNGSI BARU: EDIT ---
    public function edit($id_toko)
    {
        $toko = Toko::findOrFail($id_toko);

        // Security Check: Pastikan toko milik tenant user
        $user   = Auth::user();
        $tenant = $user->tenants->first();

        // --- FIX: Cek tenant ---
        if (! $tenant) {
            abort(403, 'Akses ditolak: User tidak memiliki tenant.');
        }

        if ($toko->id_tenant !== $tenant->id_tenant) {
            abort(403, 'Akses ditolak');
        }

        return view('owner.toko.edit', compact('toko'));
    }

    // --- FUNGSI BARU: UPDATE ---
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

        // Security Check
        $user   = Auth::user();
        $tenant = $user->tenants->first();

        // --- FIX: Cek tenant ---
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
            // is_pusat sebaiknya tidak diubah sembarangan
            'is_active' => $request->has('is_active') ? $request->is_active : $toko->is_active,
        ]);

        return redirect()->route('owner.toko.index')->with('success', 'Data toko berhasil diperbarui');
    }

    // --- FUNGSI BARU: DESTROY (HAPUS) ---
    public function destroy($id_toko)
    {
        $toko = Toko::findOrFail($id_toko);

        // Security Check
        $user   = Auth::user();
        $tenant = $user->tenants->first();

        // --- FIX: Cek tenant ---
        if (! $tenant) {
            abort(403, 'Akses ditolak: User tidak memiliki tenant.');
        }

        if ($toko->id_tenant !== $tenant->id_tenant) {
            abort(403, 'Akses ditolak');
        }

        // Opsional: Cegah penghapusan toko pusat
        if ($toko->is_pusat) {
            return redirect()->back()->with('error', 'Toko Pusat tidak dapat dihapus.');
        }

        // Hapus data (karena ada cascade di migration, user_toko_access ikut terhapus)
        $toko->delete();

        return redirect()->route('owner.toko.index')->with('success', 'Toko berhasil dihapus');
    }

    // FUNGSI PENTING: Memilih Toko Aktif
    public function select(Request $request, $id_toko)
    {
        $toko = Toko::findOrFail($id_toko);

        // Validasi apakah toko ini milik tenant user yang login
        $user   = Auth::user();
        $tenant = $user->tenants->first();

        if (! $tenant) {
            abort(403, 'Akses ditolak: User tidak memiliki tenant.');
        }

        if ($toko->id_tenant !== $tenant->id_tenant) {
            abort(403, 'Akses ditolak');
        }

        // PERBAIKAN: Simpan id_toko, BUKAN id_tenant
        session([
            'toko_active_id'   => $toko->id_toko, // <-- Sebelumnya $toko->id_tenant (salah)
            'toko_active_nama' => $toko->nama_toko,
        ]);

        return redirect()->route('owner.dashboard')->with('success', 'Berhasil beralih ke toko: ' . $toko->nama_toko);
    }
}
