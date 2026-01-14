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
        if (session()->has('toko_active_id')) {
            return session('toko_active_id');
        }

        $user   = Auth::user();
        $tenant = $user->tenants()->first();

        if (!$tenant) {
            return null;
        }

        $toko = Toko::where('id_tenant', $tenant->id_tenant)
            ->orderBy('is_pusat', 'desc')
            ->orderBy('id_toko', 'asc')
            ->first();

        if ($toko) {
            session([
                'toko_active_id'   => $toko->id_toko,
                'toko_active_nama' => $toko->nama_toko,
            ]);
            return $toko->id_toko;
        }

        return null;
    }

    public function index()
    {
        $id_toko = $this->getTokoAktif();

        if (!$id_toko) {
            return redirect()->back()->with('error', 'Akun Anda belum terhubung dengan Toko/Cabang manapun.');
        }

        $toko = Toko::find($id_toko);
        $user = Auth::user();
        $tenant = $user->tenants()->first();

        $produk = Produk::where('id_tenant', $tenant->id_tenant)
            ->with(['stokToko' => function ($q) use ($id_toko) {
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
            return redirect()->back()->with('error', 'Akun Anda belum terhubung dengan Toko/Cabang manapun.');
        }

        $toko = Toko::find($id_toko);
        $user = Auth::user();
        $tenant = $user->tenants()->first();

        $produk = Produk::where('id_tenant', $tenant->id_tenant)
            ->where('is_active', 1)
            ->orderBy('nama_produk')
            ->get();

        return view('owner.stok.tambah', compact('produk', 'toko'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_produk' => 'required|exists:produk,id_produk',
            'qty'       => 'required|numeric|min:1',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $id_toko = $this->getTokoAktif();

        if (!$id_toko) {
            return redirect()->back()->with('error', 'Sesi toko kadaluarsa.');
        }

        DB::beginTransaction();
        try {
            $stokToko = StokToko::where('id_toko', $id_toko)
                ->where('id_produk', $request->id_produk)
                ->first();

            if ($stokToko) {
                $stokToko->increment('stok_fisik', $request->qty);
            } else {
                StokToko::create([
                    'id_toko'    => $id_toko,
                    'id_produk'  => $request->id_produk,
                    'stok_fisik' => $request->qty,
                    'stok_minimal' => 5,
                ]);
            }

            DB::commit();

            return redirect()->route('owner.stok.index')
                ->with('success', 'Stok berhasil ditambahkan sebanyak ' . $request->qty . ' unit');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menambah stok: ' . $e->getMessage())
                ->withInput();
        }
    }
}
