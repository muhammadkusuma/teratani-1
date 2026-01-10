<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant;
use App\Models\Toko;

class DashboardController extends Controller
{
    // Dashboard Superadmin
    public function index()
    {
        // Logika dashboard admin (misal: hitung total tenant, total user)
        return view('dashboard');
    }

    // Dashboard Owner (Pemilik Toko)
    public function ownerIndex()
    {
        $user = Auth::user();
        
        // Ambil tenant yang terhubung dengan user ini
        // Asumsi: 1 User bisa punya banyak Tenant, kita ambil yang pertama atau aktif
        $tenant = $user->tenants()->first(); 
        
        $stats = [
            'total_toko' => 0,
            'total_produk' => 0, // Placeholder jika model belum diload
            'penjualan_hari_ini' => 0,
        ];

        if ($tenant) {
            // Contoh pengambilan data real jika relasi sudah ada
            // $stats['total_toko'] = $tenant->toko()->count();
            // $stats['total_produk'] = $tenant->produk()->count();
        }

        return view('owner.dashboard', compact('tenant', 'stats'));
    }
}