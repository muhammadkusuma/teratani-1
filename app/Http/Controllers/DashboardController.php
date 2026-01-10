<?php
namespace App\Http\Controllers;

use App\Models\SaasInvoice;
use App\Models\Tenant;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Statistik Utama
        $totalUsers    = User::count();
        $activeTenants = Tenant::where('status_langganan', 'Aktif')->count();

        // Menghitung estimasi pendapatan dari invoice yang statusnya 'Paid'
        // Jika tabel kosong, default ke 0
        $revenue = SaasInvoice::where('status_bayar', 'Paid')->sum('total_tagihan');

        // Menghitung Isu/Tiket (Jika ada tabel tiket, ganti logic ini. Sementara hardcode 0)
        $pendingIssues = 0;

        // 2. Data Grafik / Aktivitas Terbaru
        // Mengambil 5 tenant yang baru bergabung
        $recentTenants = Tenant::orderBy('created_at', 'desc')->take(5)->get();

        // Mengambil 5 user yang baru daftar
        $recentUsers = User::orderBy('created_at', 'desc')->take(5)->get();

        return view('dashboard', compact(
            'totalUsers',
            'activeTenants',
            'revenue',
            'pendingIssues',
            'recentTenants',
            'recentUsers'
        ));
    }
}
