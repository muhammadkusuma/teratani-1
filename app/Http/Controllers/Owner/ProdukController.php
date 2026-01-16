<?php
namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Satuan;
use App\Models\StokToko;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProdukController extends Controller
{
    public function index(Request $request, $id_toko)
    {
        $toko = Toko::findOrFail($id_toko);

        $query = Produk::query();

        $query->whereHas('stokTokos', function ($q) use ($id_toko) {
            $q->where('id_toko', $id_toko);
        });

        if ($request->has('search') && $request->search != null) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_produk', 'LIKE', "%{$search}%")
                    ->orWhere('sku', 'LIKE', "%{$search}%")
                    ->orWhere('barcode', 'LIKE', "%{$search}%");
            });
        }

        $produks = $query->with(['stokTokos' => function ($q) use ($id_toko) {
            $q->where('id_toko', $id_toko);
        }, 'kategori', 'satuanKecil', 'satuanBesar'])
            ->paginate(10)
            ->withQueryString();

        return view('owner.produk.index', compact('toko', 'produks'));
    }

    public function create($id_toko)
    {
        $toko = Toko::findOrFail($id_toko);
        $kategoris = Kategori::select('id_kategori', 'nama_kategori')->orderBy('nama_kategori')->get();
        $satuans = Satuan::select('id_satuan', 'nama_satuan')->orderBy('nama_satuan')->get();
        return view('owner.produk.create', compact('toko', 'kategoris', 'satuans'));
    }

    public function store(Request $request, $id_toko)
    {
        $toko = Toko::findOrFail($id_toko);

        $request->validate([
            'nama_produk'       => 'required|string|max:150',
            'sku'               => 'nullable|string|max:50|unique:produk,sku',
            'id_kategori'       => 'nullable|exists:kategori,id_kategori',
            'id_satuan_kecil'   => 'required|exists:satuan,id_satuan',
            'id_satuan_besar'   => 'nullable|exists:satuan,id_satuan',
            'nilai_konversi'    => 'required|integer|min:1',
            'harga_beli'        => 'required|integer|min:0',
            'harga_jual_umum'   => 'required|integer|min:0',
            'harga_jual_grosir' => 'nullable|integer|min:0',
            'harga_r1'          => 'nullable|integer|min:0',
            'harga_r2'          => 'nullable|integer|min:0',
            'stok_awal'         => 'nullable|integer|min:0',
            'gambar_produk'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->except(['gambar_produk', 'stok_awal', '_token']);
            $data['is_active'] = $request->has('is_active') ? 1 : 0;

            if ($request->hasFile('gambar_produk')) {
                $data['gambar_produk'] = $request->file('gambar_produk')->store('produk', 'public');
            }

            $produk = Produk::create($data);

            if ($request->filled('stok_awal') && $request->stok_awal > 0) {
                StokToko::create([
                    'id_toko'      => $toko->id_toko,
                    'id_produk'    => $produk->id_produk,
                    'stok_fisik'   => $request->stok_awal,
                    'stok_minimal' => 5,
                ]);
            } else {
                StokToko::create([
                    'id_toko'      => $toko->id_toko,
                    'id_produk'    => $produk->id_produk,
                    'stok_fisik'   => 0,
                    'stok_minimal' => 5,
                ]);
            }

            DB::commit();
            return redirect()->route('owner.toko.produk.index', $toko->id_toko)
                ->with('success', 'Produk berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id_toko, $id_produk)
    {
        $toko = Toko::findOrFail($id_toko);
        $produk = Produk::findOrFail($id_produk);
        $kategoris = Kategori::select('id_kategori', 'nama_kategori')->orderBy('nama_kategori')->get();
        $satuans = Satuan::select('id_satuan', 'nama_satuan')->orderBy('nama_satuan')->get();

        $stokToko = StokToko::where('id_toko', $toko->id_toko)
            ->where('id_produk', $produk->id_produk)
            ->first();

        return view('owner.produk.edit', compact('toko', 'produk', 'kategoris', 'satuans', 'stokToko'));
    }

    public function update(Request $request, $id_toko, $id_produk)
    {
        $toko = Toko::findOrFail($id_toko);
        $produk = Produk::findOrFail($id_produk);

        $request->validate([
            'nama_produk'       => 'required|string|max:150',
            'sku'               => 'nullable|string|max:50|unique:produk,sku,' . $produk->id_produk . ',id_produk',
            'id_satuan_kecil'   => 'required|exists:satuan,id_satuan',
            'nilai_konversi'    => 'required|integer|min:1',
            'harga_jual_umum'   => 'required|integer|min:0',
            'harga_jual_grosir' => 'nullable|integer|min:0',
            'harga_r1'          => 'nullable|integer|min:0',
            'harga_r2'          => 'nullable|integer|min:0',
            'gambar_produk'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->except(['gambar_produk', '_token', '_method']);
            $data['is_active'] = $request->has('is_active') ? 1 : 0;

            if ($request->hasFile('gambar_produk')) {
                if ($produk->gambar_produk && Storage::disk('public')->exists($produk->gambar_produk)) {
                    Storage::disk('public')->delete($produk->gambar_produk);
                }
                $data['gambar_produk'] = $request->file('gambar_produk')->store('produk', 'public');
            }

            $produk->update($data);

            DB::commit();
            return redirect()->route('owner.toko.produk.index', $toko->id_toko)
                ->with('success', 'Produk berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function destroy($id_toko, $id_produk)
    {
        $toko = Toko::findOrFail($id_toko);
        $produk = Produk::findOrFail($id_produk);

        if ($produk->gambar_produk && Storage::disk('public')->exists($produk->gambar_produk)) {
            Storage::disk('public')->delete($produk->gambar_produk);
        }

        $produk->delete();
        return redirect()->route('owner.toko.produk.index', $toko->id_toko)
            ->with('success', 'Produk dihapus');
    }
}
