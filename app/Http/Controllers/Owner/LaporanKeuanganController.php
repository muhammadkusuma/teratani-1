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
        // PERBAIKAN DISINI: Gunakan 'toko_active_id' agar sesuai dengan PengeluaranController
        $id_toko = session('toko_active_id');

        // Validasi tambahan: Jika session toko hilang/belum dipilih
        if (! $id_toko) {
            return redirect()->route('owner.dashboard')->with('error', 'Silakan pilih toko terlebih dahulu.');
        }

        // Filter Tanggal (Default: Bulan ini)
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate   = $request->input('end_date', date('Y-m-t'));

        // 1. Hitung Total Penjualan (Omset)
        $penjualan = Penjualan::where('id_toko', $id_toko)
            ->whereBetween('tgl_transaksi', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('status_transaksi', 'Selesai')
            ->get();

        $totalOmset = $penjualan->sum('total_netto');

        // 2. Hitung HPP (Harga Pokok Penjualan)
        $totalHPP = DB::table('penjualan_detail')
            ->join('penjualan', 'penjualan.id_penjualan', '=', 'penjualan_detail.id_penjualan')
            ->where('penjualan.id_toko', $id_toko)
            ->whereBetween('penjualan.tgl_transaksi', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('penjualan.status_transaksi', 'Selesai')
            ->selectRaw('SUM(penjualan_detail.qty * penjualan_detail.harga_modal_saat_jual) as total_hpp')
            ->value('total_hpp');

        // Pastikan totalHPP tidak null jika tidak ada data
        $totalHPP = $totalHPP ?? 0;

        // 3. Laba Kotor
        $labaKotor = $totalOmset - $totalHPP;

        // 4. Hitung Pengeluaran Operasional (Gaji, Listrik, dll)
        // Pastikan id_toko sudah benar agar query ini berjalan
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
