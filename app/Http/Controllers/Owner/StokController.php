<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\StokToko;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StokController extends Controller
{
    private function getTokoAktif()
    {
        return session('toko_active_id');
    }

    public function index(Request $request)
    {
        $id_toko = $this->getTokoAktif();

        if (!$id_toko) {
            return redirect()->back()->with('error', 'Akun Anda belum terhubung dengan Toko/Cabang manapun.');
        }

        $toko = Toko::find($id_toko);
        $gudangs = \App\Models\Gudang::where('id_toko', $id_toko)->get();

        // Load all products with stock from all locations
        $query = Produk::with([
            'kategori', 
            'satuanKecil',
            'stokTokos' => function($q) use ($id_toko) {
                $q->where('id_toko', $id_toko);
            },
            'stokGudangs' => function($q) use ($id_toko) {
                $q->whereHas('gudang', function($gq) use ($id_toko) {
                    $gq->where('id_toko', $id_toko);
                })->with('gudang');
            }
        ])->orderBy('nama_produk');

        $produk = $query->paginate(20);

        return view('owner.stok.index', compact('produk', 'toko', 'gudangs'));
    }

    public function tambah()
    {
        $id_toko = $this->getTokoAktif();

        if (!$id_toko) {
            return redirect()->back()->with('error', 'Toko belum dipilih');
        }

        $toko = Toko::find($id_toko);
        $gudangs = \App\Models\Gudang::where('id_toko', $id_toko)->get();

        $produk = Produk::select('id_produk', 'nama_produk', 'sku')
            ->where('is_active', 1)
            ->orderBy('nama_produk')
            ->get();

        return view('owner.stok.tambah', compact('produk', 'toko', 'gudangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_produk' => 'required|exists:produk,id_produk',
            'jumlah'    => 'required|numeric|min:1',
            'location_type' => 'required|in:toko,gudang',
            'location_id' => 'required',
            'keterangan' => 'nullable|string'
        ]);

        $id_toko = $this->getTokoAktif();

        if (!$id_toko) {
            return redirect()->back()->with('error', 'Toko belum dipilih');
        }

        DB::beginTransaction();
        try {
            $stokAkhir = 0;
            
            if ($request->location_type == 'gudang') {
                $stok = \App\Models\StokGudang::firstOrCreate(
                    ['id_gudang' => $request->location_id, 'id_produk' => $request->id_produk],
                    ['stok_fisik' => 0]
                );
                $stok->increment('stok_fisik', $request->jumlah);
                $stokAkhir = $stok->stok_fisik;
            } else {
                $stok = StokToko::firstOrCreate(
                    ['id_toko' => $request->location_id, 'id_produk' => $request->id_produk],
                    ['stok_fisik' => 0, 'stok_minimal' => 5]
                );
                $stok->increment('stok_fisik', $request->jumlah);
                $stokAkhir = $stok->stok_fisik;
            }

            // Log History
            \App\Models\RiwayatStok::create([
                'id_produk' => $request->id_produk,
                'id_toko' => $request->location_type == 'toko' ? $request->location_id : null,
                'id_gudang' => $request->location_type == 'gudang' ? $request->location_id : null,
                'jenis' => 'masuk',
                'jumlah' => $request->jumlah,
                'stok_akhir' => $stokAkhir,
                'keterangan' => $request->keterangan ?? 'Penambahan Stok Manual',
                'referensi' => 'MANUAL-ADD',
                'tanggal' => now(),
            ]);

            DB::commit();
            return redirect()->route('owner.stok.index', [
                'location_type' => $request->location_type, 
                'location_id' => $request->location_id
            ])->with('success', 'Stok berhasil ditambahkan');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menambah stok: ' . $e->getMessage());
        }
    }
}
