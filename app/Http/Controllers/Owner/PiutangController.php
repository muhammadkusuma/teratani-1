<?php
namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\KartuPiutang;
use App\Models\PembayaranPiutang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PiutangController extends Controller
{
    // Menampilkan daftar piutang (belum lunas)
    public function index()
    {
        $id_toko = session('toko_active_id');

        $piutangs = KartuPiutang::with(['pelanggan', 'penjualan'])
            ->where('id_toko', $id_toko)
            ->where('status', 'Belum Lunas') // Hanya tampilkan yang belum lunas
            ->orderBy('tgl_jatuh_tempo', 'asc')
            ->paginate(10);

        return view('owner.piutang.index', compact('piutangs'));
    }

    // Menampilkan detail piutang dan form pembayaran
    public function show($id)
    {
        $id_toko = session('toko_active_id');

        $piutang = KartuPiutang::with(['pelanggan', 'penjualan', 'pembayarans'])
            ->where('id_toko', $id_toko)
            ->findOrFail($id);

        return view('owner.piutang.show', compact('piutang'));
    }

    // Proses simpan pembayaran cicilan
    public function storePayment(Request $request, $id)
    {
        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:1',
            'tgl_bayar'    => 'required|date',
            'metode_bayar' => 'required|in:Tunai,Transfer',
        ]);

        $id_toko = session('toko_active_id');
        $piutang = KartuPiutang::where('id_toko', $id_toko)->findOrFail($id);

        // Validasi: Pembayaran tidak boleh melebihi sisa hutang
        if ($request->jumlah_bayar > $piutang->sisa_piutang) {
            return back()->with('error', 'Jumlah pembayaran melebihi sisa hutang! Sisa: Rp ' . number_format($piutang->sisa_piutang, 0));
        }

        DB::beginTransaction();
        try {
            // 1. Simpan Pembayaran
            PembayaranPiutang::create([
                'id_piutang'   => $piutang->id_piutang,
                'no_kuitansi'  => 'KUIT-' . time(), // Contoh generate nomor
                'tgl_bayar'    => $request->tgl_bayar,
                'jumlah_bayar' => $request->jumlah_bayar,
                'metode_bayar' => $request->metode_bayar,
                'keterangan'   => $request->keterangan,
                'id_user'      => Auth::id(),
            ]);

            // 2. Update Kartu Piutang
            $piutang->sudah_dibayar += $request->jumlah_bayar;
            $piutang->sisa_piutang  -= $request->jumlah_bayar;

            // Cek Lunas
            if ($piutang->sisa_piutang <= 0) {
                $piutang->status       = 'Lunas';
                $piutang->sisa_piutang = 0;

                // Opsional: Update status di tabel Penjualan juga jika perlu
                $piutang->penjualan()->update(['status_bayar' => 'Lunas']);
            } else {
                $piutang->penjualan()->update(['status_bayar' => 'Sebagian']);
            }

            $piutang->save();

            DB::commit();
            return back()->with('success', 'Pembayaran berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
