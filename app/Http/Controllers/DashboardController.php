<?php
namespace App\Http\Controllers;

use App\Models\Toko;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // Dashboard Superadmin
    public function index()
    {
        // Logika dashboard admin (misal: hitung total tenant, total user)
        return view('dashboard');
    }

    // Dashboard Owner (Pemilik Toko)
    // Dashboard Owner (Pemilik Toko)
    public function ownerIndex()
    {
        $user = Auth::user();

        // Ambil tenant pertama milik user
        $tenant = $user->tenants->first();

        // --- UPDATE: Redirect jika belum punya bisnis ---
        if (! $tenant) {
            return redirect()->route('owner.bisnis.create')
                ->with('info', 'Halo! Silakan daftarkan Bisnis Utama Anda terlebih dahulu sebelum melanjutkan.');
        }
        // ------------------------------------------------

        // Logika Pilih Toko Otomatis (Tetap sama seperti sebelumnya)
        if (! session()->has('toko_active_id')) {
            $jumlahToko = Toko::where('id_tenant', $tenant->id_tenant)->count();

            if ($jumlahToko == 1) {
                $tokoSatu = Toko::where('id_tenant', $tenant->id_tenant)->first();
                session([
                    'toko_active_id'   => $tokoSatu->id_toko,
                    'toko_active_nama' => $tokoSatu->nama_toko,
                ]);
            } elseif ($jumlahToko > 1) {
                return redirect()->route('owner.toko.index')->with('info', 'Silakan pilih toko untuk dikelola.');
            }
        }

        $stats = [
            'nama_toko_aktif'    => session('toko_active_nama', 'Belum Pilih Toko'),
            'penjualan_hari_ini' => 0, // Nanti diganti query real
        ];

        return view('owner.dashboard', compact('tenant', 'stats'));
    }
}
