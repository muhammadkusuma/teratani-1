<?php
namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Satuan;
use App\Models\StokToko;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProdukController extends Controller
{
    public function index(Request $request, $id_toko)
    {
        $toko = Toko::find($id_toko);

        if ($toko->id_tenant !== Auth::user()->tenants->first()->id_tenant) {
            abort(403);
        }

        $query = Produk::where('id_tenant', $toko->id_tenant);

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

    public function create(Toko $toko)
    {
        $kategoris = Kategori::where('id_tenant', $toko->id_tenant)->get();
        $satuans   = Satuan::where('id_tenant', $toko->id_tenant)->get();
        return view('owner.produk.create', compact('toko', 'kategoris', 'satuans'));
    }

    public function store(Request $request, Toko $toko)
    {
        $tenantId = $toko->id_tenant;

        $request->validate([
            'nama_produk'          => 'required|string|max:150',
            'sku'                  => [
                'nullable', 'string', 'max:50',
                Rule::unique('produk')->where(function ($query) use ($tenantId) {
                    return $query->where('id_tenant', $tenantId);
                }),
            ],
            'id_kategori'          => 'nullable|exists:kategori,id_kategori',
            'id_satuan_kecil'      => 'required|exists:satuan,id_satuan',
            'id_satuan_besar'      => 'nullable|exists:satuan,id_satuan',
            'nilai_konversi'       => 'required|integer|min:1',
            'harga_beli_rata_rata' => 'required|numeric|min:0',
            'harga_jual_umum'      => 'required|numeric|min:0',
            'stok_awal'            => 'nullable|integer|min:0',
            'gambar_produk'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $data              = $request->except(['gambar_produk', 'stok_awal', '_token']);
            $data['id_tenant'] = $tenantId;
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

    public function edit(Toko $toko, Produk $produk)
    {
        if ($produk->id_tenant !== $toko->id_tenant) {
            abort(403);
        }

        $kategoris = Kategori::where('id_tenant', $toko->id_tenant)->get();
        $satuans   = Satuan::where('id_tenant', $toko->id_tenant)->get();

        $stokToko = StokToko::where('id_toko', $toko->id_toko)
            ->where('id_produk', $produk->id_produk)
            ->first();

        return view('owner.produk.edit', compact('toko', 'produk', 'kategoris', 'satuans', 'stokToko'));
    }

    public function update(Request $request, Toko $toko, Produk $produk)
    {
        if ($produk->id_tenant !== $toko->id_tenant) {
            abort(403);
        }

        $request->validate([
            'nama_produk'     => 'required|string|max:150',
            'sku'             => [
                'nullable', 'string', 'max:50',
                Rule::unique('produk')->ignore($produk->id_produk, 'id_produk')->where(function ($query) use ($toko) {
                    return $query->where('id_tenant', $toko->id_tenant);
                }),
            ],
            'id_satuan_kecil' => 'required|exists:satuan,id_satuan',
            'nilai_konversi'  => 'required|integer|min:1',
            'harga_jual_umum' => 'required|numeric|min:0',
            'gambar_produk'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $data              = $request->except(['gambar_produk', '_token', '_method']);
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

    public function destroy(Toko $toko, Produk $produk)
    {
        if ($produk->id_tenant !== $toko->id_tenant) {
            abort(403);
        }

        if ($produk->gambar_produk && Storage::disk('public')->exists($produk->gambar_produk)) {
            Storage::disk('public')->delete($produk->gambar_produk);
        }

        $produk->delete();
        return redirect()->route('owner.toko.produk.index', $toko->id_toko)
            ->with('success', 'Produk dihapus');
    }
}
