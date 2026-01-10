<?php
namespace App\Http\Controllers;

use App\Models\Toko;
use App\Models\Tenant;
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

        // Ambil tenant yang terhubung dengan user ini
        $tenant = $user->tenants()->first();

        // --- FIX: Cek apakah Tenant ada? ---
        if (! $tenant) {
            // Jika tidak ada tenant, tampilkan dashboard kosong atau arahkan buat tenant
            return view('owner.dashboard', [
                'tenant' => null,
                'stats'  => [
                    'nama_toko_aktif'    => 'Belum Ada Bisnis',
                    'penjualan_hari_ini' => 0,
                ],
            ]);
        }
        // -----------------------------------

        // Logika Pilih Toko Otomatis
        if (! session()->has('toko_active_id')) {
            // Cek jumlah toko milik tenant ini
            // (Sekarang aman diakses karena $tenant dipastikan ada)
            $jumlahToko = Toko::where('id_tenant', $tenant->id_tenant)->count();

            if ($jumlahToko == 1) {
                // Jika cuma 1, otomatis set session
                $tokoSatu = Toko::where('id_tenant', $tenant->id_tenant)->first();
                session([
                    'toko_active_id'   => $tokoSatu->id_toko,
                    'toko_active_nama' => $tokoSatu->nama_toko,
                ]);
            } elseif ($jumlahToko > 1) {
                // Jika lebih dari 1, redirect ke halaman list toko untuk memilih
                return redirect()->route('owner.toko.index')->with('info', 'Silakan pilih toko untuk dikelola.');
            }
            // Jika toko 0, biarkan lolos ke dashboard agar user bisa melihat tombol "Buat Toko"
        }

        // Ambil Data Statistik Dashboard
        $activeTokoId = session('toko_active_id');

        $stats = [
            'nama_toko_aktif'    => session('toko_active_nama', 'Belum Pilih Toko'),
            'penjualan_hari_ini' => 0,
        ];

        return view('owner.dashboard', compact('tenant', 'stats'));
    }
}
