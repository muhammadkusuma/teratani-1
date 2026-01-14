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

        $tenant = $user->tenants->first();
        if (! $tenant) {
            return redirect()->route('owner.bisnis.create')
                ->with('info', 'Halo! Silakan daftarkan Bisnis Utama Anda terlebih dahulu.');
        }

        $activeTokoId  = session('toko_active_id');
        $tokoIds       = [];
        $namaTokoAktif = 'Semua Cabang (Global)';

        if ($activeTokoId) {
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
            $tokoIds = Toko::where('id_tenant', $tenant->id_tenant)->pluck('id_toko');
        }

        $data['tenant']          = $tenant;
        $data['nama_toko_aktif'] = $namaTokoAktif;
        $data['total_toko']      = Toko::where('id_tenant', $tenant->id_tenant)->count();

        $data['total_produk'] = Produk::where('id_tenant', $tenant->id_tenant)->count();

        $data['omset_hari_ini'] = Penjualan::whereIn('id_toko', $tokoIds)
            ->whereDate('tgl_transaksi', Carbon::today())
            ->sum('total_netto');

        $data['transaksi_hari_ini'] = Penjualan::whereIn('id_toko', $tokoIds)
            ->whereDate('tgl_transaksi', Carbon::today())
            ->count();

        $data['stok_menipis'] = DB::table('stok_toko')
            ->join('produk', 'stok_toko.id_produk', '=', 'produk.id_produk')
            ->join('toko', 'stok_toko.id_toko', '=', 'toko.id_toko')
            ->whereIn('stok_toko.id_toko', $tokoIds)
            ->where('stok_toko.stok_fisik', '<=', 10)
            ->select('produk.nama_produk', 'toko.nama_toko', 'stok_toko.stok_fisik as sisa_stok')
            ->orderBy('stok_toko.stok_fisik', 'asc')
            ->limit(5)
            ->get();

        $data['produk_terlaris'] = DB::table('penjualan_detail')
            ->join('penjualan', 'penjualan_detail.id_penjualan', '=', 'penjualan.id_penjualan')
            ->join('produk', 'penjualan_detail.id_produk', '=', 'produk.id_produk')
            ->whereIn('penjualan.id_toko', $tokoIds)
            ->whereMonth('penjualan.tgl_transaksi', Carbon::now()->month)
            ->select('produk.nama_produk', DB::raw('sum(penjualan_detail.qty) as total_terjual'))
            ->groupBy('produk.id_produk', 'produk.nama_produk')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date        = Carbon::now()->subDays($i);
            $chartData[] = [
                'hari'  => $date->format('D'),
                'total' => Penjualan::whereIn('id_toko', $tokoIds)
                    ->whereDate('tgl_transaksi', $date)
                    ->sum('total_netto'),
            ];
        }
        $data['chart_data'] = $chartData;

        return view('owner.dashboard', $data);
    }
}
