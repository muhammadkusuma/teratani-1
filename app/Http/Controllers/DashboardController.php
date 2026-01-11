<?php
namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\Toko;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function ownerIndex()
    {
        $user = Auth::user();

        // 1. Cek Tenant (Bisnis)
        $tenant = $user->tenants->first();
        if (! $tenant) {
            return redirect()->route('owner.bisnis.create')
                ->with('info', 'Halo! Silakan daftarkan Bisnis Utama Anda terlebih dahulu.');
        }

        // ------------------------------------------------------------------
        // LOGIC BARU: Filter Berdasarkan Toko yang Di-switch (Active Session)
        // ------------------------------------------------------------------
        $activeTokoId  = session('toko_active_id');
        $tokoIds       = [];
        $namaTokoAktif = 'Semua Cabang (Global)';

        if ($activeTokoId) {
            // Cek validitas toko
            $tokoAktif = Toko::where('id_tenant', $tenant->id_tenant)
                ->where('id_toko', $activeTokoId)
                ->first();

            if ($tokoAktif) {
                $tokoIds       = collect([$tokoAktif->id_toko]);
                $namaTokoAktif = $tokoAktif->nama_toko;
            } else {
                $tokoIds = Toko::where('id_tenant', $tenant->id_tenant)->pluck('id_toko');
            }
        } else {
            // Default global jika belum pilih toko
            $tokoIds = Toko::where('id_tenant', $tenant->id_tenant)->pluck('id_toko');
        }
        // ------------------------------------------------------------------

        // 3. Statistik Card Utama
        $data['tenant']          = $tenant;
        $data['nama_toko_aktif'] = $namaTokoAktif; // Variabel untuk ditampilkan di view
        $data['total_toko']      = Toko::where('id_tenant', $tenant->id_tenant)->count();

        // Total Produk (Master Data)
        $data['total_produk'] = Produk::where('id_tenant', $tenant->id_tenant)->count();

        // Statistik Penjualan (Harian) - Filtered by $tokoIds
        $data['omset_hari_ini'] = Penjualan::whereIn('id_toko', $tokoIds)
            ->whereDate('tgl_transaksi', Carbon::today())
            ->sum('total_netto');

        $data['transaksi_hari_ini'] = Penjualan::whereIn('id_toko', $tokoIds)
            ->whereDate('tgl_transaksi', Carbon::today())
            ->count();

        // 4. Insight: Stok Menipis (Limit <= 10)
        // PERBAIKAN: Menggunakan threshold 10 dan filter toko aktif
        $data['stok_menipis'] = DB::table('stok_toko')
            ->join('produk', 'stok_toko.id_produk', '=', 'produk.id_produk')
            ->join('toko', 'stok_toko.id_toko', '=', 'toko.id_toko')
            ->whereIn('stok_toko.id_toko', $tokoIds)  // Ikut filter toko
            ->where('stok_toko.stok_fisik', '<=', 10) // <-- UPDATE: Stok <= 10
            ->select('produk.nama_produk', 'toko.nama_toko', 'stok_toko.stok_fisik as sisa_stok')
            ->orderBy('stok_toko.stok_fisik', 'asc')
            ->limit(5)
            ->get();

        // 5. Insight: 5 Produk Terlaris Bulan Ini
        $data['produk_terlaris'] = DB::table('penjualan_detail')
            ->join('penjualan', 'penjualan_detail.id_penjualan', '=', 'penjualan.id_penjualan')
            ->join('produk', 'penjualan_detail.id_produk', '=', 'produk.id_produk')
            ->whereIn('penjualan.id_toko', $tokoIds) // Ikut filter toko
            ->whereMonth('penjualan.tgl_transaksi', Carbon::now()->month)
            ->select('produk.nama_produk', DB::raw('sum(penjualan_detail.qty) as total_terjual'))
            ->groupBy('produk.id_produk', 'produk.nama_produk')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        // 6. Grafik Penjualan 7 Hari Terakhir
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date        = Carbon::now()->subDays($i);
            $chartData[] = [
                'hari'  => $date->format('D'),
                'total' => Penjualan::whereIn('id_toko', $tokoIds) // Ikut filter toko
                    ->whereDate('tgl_transaksi', $date)
                    ->sum('total_netto'),
            ];
        }
        $data['chart_data'] = $chartData;

        return view('owner.dashboard', $data);
    }
}
