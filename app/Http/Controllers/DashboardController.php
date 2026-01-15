<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function ownerIndex()
    {
        $id_toko = session('toko_active_id');
        $nama_toko_aktif = session('toko_active_nama', 'Semua Toko');

        // Load user's stores for popup selection
        $userStores = Toko::where('id_perusahaan', Auth::user()->id_perusahaan)
            ->orderBy('is_pusat', 'desc')
            ->orderBy('nama_toko')
            ->get();

        $total_toko = Toko::where('id_perusahaan', Auth::user()->id_perusahaan)->count();
        $total_produk = Produk::count();
        $total_pelanggan = Pelanggan::count();

        if ($id_toko) {
            $omset_hari_ini = Penjualan::where('id_toko', $id_toko)
                ->whereDate('tgl_transaksi', today())
                ->sum('total_netto');

            $transaksi_hari_ini = Penjualan::where('id_toko', $id_toko)
                ->whereDate('tgl_transaksi', today())
                ->count();

            $total_pelanggan = Pelanggan::where('id_toko', $id_toko)->count();
        } else {
            $omset_hari_ini = Penjualan::whereDate('tgl_transaksi', today())->sum('total_netto');
            $transaksi_hari_ini = Penjualan::whereDate('tgl_transaksi', today())->count();
        }

        // Chart data untuk 7 hari terakhir
        $chart_data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $total = $id_toko 
                ? Penjualan::where('id_toko', $id_toko)->whereDate('tgl_transaksi', $date)->sum('total_netto')
                : Penjualan::whereDate('tgl_transaksi', $date)->sum('total_netto');
            
            $chart_data[] = [
                'hari' => $date->format('D'),
                'total' => $total
            ];
        }

        // Stok menipis
        $stok_menipis = DB::table('stok_toko')
            ->join('produk', 'stok_toko.id_produk', '=', 'produk.id_produk')
            ->join('toko', 'stok_toko.id_toko', '=', 'toko.id_toko')
            ->where('stok_toko.stok_fisik', '<=', 10)
            ->when($id_toko, function($q) use ($id_toko) {
                return $q->where('stok_toko.id_toko', $id_toko);
            })
            ->select('produk.nama_produk', 'toko.nama_toko', 'stok_toko.stok_fisik as sisa_stok')
            ->orderBy('stok_toko.stok_fisik', 'asc')
            ->limit(5)
            ->get();

        // Produk terlaris bulan ini
        $produk_terlaris = DB::table('penjualan_detail')
            ->join('penjualan', 'penjualan_detail.id_penjualan', '=', 'penjualan.id_penjualan')
            ->join('produk', 'penjualan_detail.id_produk', '=', 'produk.id_produk')
            ->whereMonth('penjualan.tgl_transaksi', now()->month)
            ->when($id_toko, function($q) use ($id_toko) {
                return $q->where('penjualan.id_toko', $id_toko);
            })
            ->select('produk.nama_produk', DB::raw('sum(penjualan_detail.qty) as total_terjual'))
            ->groupBy('produk.id_produk', 'produk.nama_produk')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        return view('owner.dashboard', compact(
            'nama_toko_aktif',
            'userStores',
            'total_toko',
            'total_produk',
            'total_pelanggan',
            'omset_hari_ini',
            'transaksi_hari_ini',
            'chart_data',
            'stok_menipis',
            'produk_terlaris'
        ));
    }

    public function index()
    {
        return view('dashboard');
    }
}
