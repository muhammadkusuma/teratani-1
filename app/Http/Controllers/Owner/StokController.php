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

    public function index()
    {
        $id_toko = $this->getTokoAktif();

        if (!$id_toko) {
            return redirect()->back()->with('error', 'Akun Anda belum terhubung dengan Toko/Cabang manapun.');
        }

        $toko = Toko::find($id_toko);

        $produk = Produk::with(['stokToko' => function ($q) use ($id_toko) {
                $q->where('id_toko', $id_toko);
            }, 'kategori', 'satuanKecil'])
            ->orderBy('nama_produk')
            ->paginate(20);

        return view('owner.stok.index', compact('produk', 'toko'));
    }

    public function tambah()
    {
        $id_toko = $this->getTokoAktif();

        if (!$id_toko) {
            return redirect()->back()->with('error', 'Toko belum dipilih');
        }

        $toko = Toko::find($id_toko);

        $produk = Produk::orderBy('nama_produk')->get();

        return view('owner.stok.tambah', compact('produk', 'toko'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_produk' => 'required|exists:produk,id_produk',
            'jumlah'    => 'required|numeric|min:1',
        ]);

        $id_toko = $this->getTokoAktif();

        if (!$id_toko) {
            return redirect()->back()->with('error', 'Toko belum dipilih');
        }

        DB::beginTransaction();
        try {
            $stok = StokToko::where('id_toko', $id_toko)
                ->where('id_produk', $request->id_produk)
                ->first();

            if ($stok) {
                $stok->stok_fisik += $request->jumlah;
                $stok->save();
            } else {
                StokToko::create([
                    'id_toko'      => $id_toko,
                    'id_produk'    => $request->id_produk,
                    'stok_fisik'   => $request->jumlah,
                    'stok_minimal' => 5,
                ]);
            }

            DB::commit();
            return redirect()->route('owner.stok.index')->with('success', 'Stok berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menambah stok: ' . $e->getMessage());
        }
    }
}
