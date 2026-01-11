<?php
namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Pengeluaran;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanKeuanganController extends Controller
{
    public function index(Request $request)
    {
        $id_toko = session('id_toko_aktif');

        // Filter Tanggal (Default: Bulan ini)
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate   = $request->input('end_date', date('Y-m-t'));

        // 1. Hitung Total Penjualan (Omset)
        // Menggunakan status 'Selesai' agar valid
        $penjualan = Penjualan::where('id_toko', $id_toko)
            ->whereBetween('tgl_transaksi', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('status_transaksi', 'Selesai')
            ->get();

        $totalOmset = $penjualan->sum('total_netto');

        // 2. Hitung HPP (Harga Pokok Penjualan)
        // Join ke penjualan_detail untuk mengambil harga modal saat barang terjual
        $totalHPP = DB::table('penjualan_detail')
            ->join('penjualan', 'penjualan.id_penjualan', '=', 'penjualan_detail.id_penjualan')
            ->where('penjualan.id_toko', $id_toko)
            ->whereBetween('penjualan.tgl_transaksi', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('penjualan.status_transaksi', 'Selesai')
            ->selectRaw('SUM(penjualan_detail.qty * penjualan_detail.harga_modal_saat_jual) as total_hpp')
            ->value('total_hpp');

        // 3. Laba Kotor
        $labaKotor = $totalOmset - $totalHPP;

        // 4. Hitung Pengeluaran Operasional (Gaji, Listrik, dll)
        $totalPengeluaran = Pengeluaran::where('id_toko', $id_toko)
            ->whereBetween('tgl_pengeluaran', [$startDate, $endDate])
            ->sum('nominal');

        // 5. Laba Bersih
        $labaBersih = $labaKotor - $totalPengeluaran;

        return view('owner.laporan.keuangan', compact(
            'startDate', 'endDate',
            'totalOmset', 'totalHPP', 'labaKotor',
            'totalPengeluaran', 'labaBersih'
        ));
    }
}
