<?php
namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\MutasiDetail;
use App\Models\MutasiStok;
use App\Models\Produk;
use App\Models\StokToko;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MutasiController extends Controller
{
    public function index()
    {
        // Ambil data mutasi milik tenant user yang sedang login
        // Asumsi user punya id_tenant. Jika tidak, sesuaikan query ini.
        $user = Auth::user();

        $mutasi = MutasiStok::with(['tokoAsal', 'tokoTujuan', 'pengirim'])
            ->where('id_tenant', $user->id_tenant ?? 1) // Default 1 jika null (dev)
            ->orderBy('tgl_kirim', 'desc')
            ->paginate(10);

        return view('owner.mutasi.index', compact('mutasi'));
    }

    public function create()
    {
        // Ambil daftar toko untuk dropdown
        $tokos = Toko::where('id_tenant', Auth::user()->id_tenant ?? 1)->get();
        // Ambil semua produk
        $produks = Produk::where('id_tenant', Auth::user()->id_tenant ?? 1)->get();

        return view('owner.mutasi.create', compact('tokos', 'produks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_toko_asal'      => 'required|different:id_toko_tujuan',
            'id_toko_tujuan'    => 'required',
            'tgl_kirim'         => 'required|date',
            'items'             => 'required|array|min:1',
            'items.*.id_produk' => 'required',
            'items.*.qty'       => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            // 1. Buat Header Mutasi
            $mutasi = MutasiStok::create([
                'id_tenant'        => Auth::user()->id_tenant ?? 1,
                'no_mutasi'        => 'TRF-' . time(), // Generate nomor otomatis
                'id_toko_asal'     => $request->id_toko_asal,
                'id_toko_tujuan'   => $request->id_toko_tujuan,
                'tgl_kirim'        => $request->tgl_kirim,
                'status'           => 'Proses',
                'keterangan'       => $request->keterangan,
                'id_user_pengirim' => Auth::id(),
            ]);

            foreach ($request->items as $item) {
                // 2. Simpan Detail Mutasi
                MutasiDetail::create([
                    'id_mutasi'  => $mutasi->id_mutasi,
                    'id_produk'  => $item['id_produk'],
                    'qty_kirim'  => $item['qty'],
                    'qty_terima' => 0, // Belum diterima
                ]);

                // 3. Kurangi Stok Fisik di Toko Asal (Booking Stok)
                $stokAsal = StokToko::where('id_toko', $request->id_toko_asal)
                    ->where('id_produk', $item['id_produk'])
                    ->first();

                if ($stokAsal) {
                    $stokAsal->decrement('stok_fisik', $item['qty']);
                } else {
                    // Jika data stok belum ada, buat baru (minus) atau handle error
                    StokToko::create([
                        'id_toko'    => $request->id_toko_asal,
                        'id_produk'  => $item['id_produk'],
                        'stok_fisik' => -($item['qty']),
                    ]);
                }
            }
        });

        return redirect()->route('owner.mutasi.index')->with('success', 'Transfer stok berhasil dibuat!');
    }
}
