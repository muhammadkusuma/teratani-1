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
        // Omset dihitung dari total_netto (sudah dikurangi diskon)
        $penjualan = Penjualan::where('id_toko', $id_toko)
            ->whereBetween('tgl_transaksi', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('status_transaksi', 'Selesai') // Hanya transaksi sukses
            ->get();

        $totalOmset = $penjualan->sum('total_netto');

        // -----------------------------------------------------------
        // B. HITUNG HPP (Harga Pokok Penjualan) - LOGIC FALLBACK
        // -----------------------------------------------------------
        // Jika 'harga_modal_saat_jual' di tabel transaksi 0 (bug lama),
        // gunakan 'harga_beli_rata_rata' dari tabel master produk.
        $totalHPP = DB::table('penjualan_detail')
            ->join('penjualan', 'penjualan.id_penjualan', '=', 'penjualan_detail.id_penjualan')
            ->join('produk', 'penjualan_detail.id_produk', '=', 'produk.id_produk') // Join ke master produk
            ->where('penjualan.id_toko', $id_toko)
            ->whereBetween('penjualan.tgl_transaksi', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('penjualan.status_transaksi', 'Selesai')
            ->selectRaw('
                SUM(
                    penjualan_detail.qty * CASE 
                        WHEN penjualan_detail.harga_modal_saat_jual > 0 
                        THEN penjualan_detail.harga_modal_saat_jual 
                        ELSE produk.harga_beli_rata_rata 
                    END
                ) as total_hpp
            ')
            ->value('total_hpp');

        $totalHPP = $totalHPP ?? 0;

        // -----------------------------------------------------------
        // C. HITUNG LABA KOTOR
        // -----------------------------------------------------------
        $labaKotor = $totalOmset - $totalHPP;

        // -----------------------------------------------------------
        // D. HITUNG PENGELUARAN (Biaya Operasional)
        // -----------------------------------------------------------
        // Menggunakan whereDate agar rentang tanggal inklusif
        $totalPengeluaran = Pengeluaran::where('id_toko', $id_toko)
            ->whereBetween('tgl_pengeluaran', [$startDate, $endDate])
            ->sum('nominal');

        // -----------------------------------------------------------
        // E. HITUNG LABA BERSIH
        // -----------------------------------------------------------
        $labaBersih = $labaKotor - $totalPengeluaran;

        return view('owner.laporan.keuangan', compact(
            'startDate', 'endDate',
            'totalOmset', 'totalHPP', 'labaKotor',
            'totalPengeluaran', 'labaBersih'
        ));
    }
}