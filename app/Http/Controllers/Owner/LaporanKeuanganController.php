<?php
namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Pembelian; // [Tambahan] Import Model Pembelian
use App\Models\Pengeluaran;
use App\Models\Penjualan;
use Illuminate\Http\Request;

class LaporanKeuanganController extends Controller
{
    public function index(Request $request)
    {
        // 1. Pastikan Toko Dipilih
        $id_toko = session('toko_active_id');

        if (! $id_toko) {
            return redirect()->route('owner.dashboard')->with('error', 'Silakan pilih toko terlebih dahulu.');
        }

        // 2. Filter Tanggal
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate   = $request->input('end_date', date('Y-m-t'));

        // -----------------------------------------------------------
        // A. HITUNG OMSET (Pendapatan Bersih)
        // -----------------------------------------------------------
        $penjualan = Penjualan::where('id_toko', $id_toko)
            ->whereBetween('tgl_transaksi', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('status_transaksi', 'Selesai')
            ->get();

        $totalOmset = $penjualan->sum('total_netto');

        // -----------------------------------------------------------
        // B. HITUNG TOTAL PEMBELIAN (Pengganti HPP / Cost of Goods)
        // -----------------------------------------------------------
        // [PERBAIKAN] Menghitung total pembelian barang pada periode ini
        $totalPembelian = Pembelian::where('id_toko', $id_toko)
            ->whereBetween('tgl_pembelian', [$startDate, $endDate])
            ->sum('total_pembelian');

        // Opsional: Jika masih ingin menampilkan HPP sebagai referensi data (tidak mempengaruhi laba rugi jika pakai metode pembelian)
        // $totalHPP = ... (kode lama bisa di-comment atau dibiarkan jika ingin ditampilkan saja)

        // -----------------------------------------------------------
        // C. HITUNG LABA KOTOR
        // -----------------------------------------------------------
        // Rumus: Omset - Total Pembelian Barang
        $labaKotor = $totalOmset - $totalPembelian;

        // -----------------------------------------------------------
        // D. HITUNG PENGELUARAN (Biaya Operasional)
        // -----------------------------------------------------------
        // [PERBAIKAN] Filter 'kategori_biaya' != 'Pembelian Stok'
        // agar tidak double counting dengan data di poin B.
        $totalPengeluaran = Pengeluaran::where('id_toko', $id_toko)
            ->whereBetween('tgl_pengeluaran', [$startDate, $endDate])
            ->where('kategori_biaya', '!=', 'Pembelian Stok') // Exclude pembayaran beli stok
            ->sum('nominal');

        // -----------------------------------------------------------
        // E. HITUNG LABA BERSIH
        // -----------------------------------------------------------
        $labaBersih = $labaKotor - $totalPengeluaran;

        return view('owner.laporan.keuangan', compact(
            'startDate', 'endDate',
            'totalOmset', 'totalPembelian', 'labaKotor', // Ganti totalHPP dengan totalPembelian
            'totalPengeluaran', 'labaBersih'
        ));
    }
}
