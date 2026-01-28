<?php
namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Gudang;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Satuan;
use App\Models\StokGudang;
use App\Models\StokToko;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProdukController extends Controller
{
    public function index(Request $request, $id_toko)
    {
        $toko = Toko::findOrFail($id_toko);
        
        // Get all tokos for the selector
        $allTokos = Toko::where('id_perusahaan', $toko->id_perusahaan)
            ->where('is_active', 1)
            ->orderBy('nama_toko')
            ->get();

        // Determine which toko to display products for
        $selectedTokoId = $request->get('selected_toko', $id_toko);
        $selectedToko = Toko::find($selectedTokoId) ?? $toko;

        $query = Produk::query()
            ->select([
                'id_produk',
                'nama_produk',
                'sku',
                'barcode',
                'gambar_produk',
                'is_active',
                'harga_jual_umum',
                'id_kategori',
                'id_satuan_kecil',
                'id_satuan_besar',
                'nilai_konversi'
            ]);

        // Filter products that exist in selected toko
        $query->whereHas('stokTokos', function ($q) use ($selectedTokoId) {
            $q->where('id_toko', $selectedTokoId);
        });

        if ($request->has('search') && $request->search != null) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_produk', 'LIKE', "%{$search}%")
                    ->orWhere('sku', 'LIKE', "%{$search}%")
                    ->orWhere('barcode', 'LIKE', "%{$search}%");
            });
        }

        $produks = $query->with([
            // Load ALL toko stocks (we'll filter in the view)
            'stokTokos' => function ($q) {
                $q->select('id_produk', 'id_toko', 'stok_fisik', 'stok_minimal')
                  ->with('toko:id_toko,nama_toko');
            },
            // All warehouse stocks (from all tokos)
            'stokGudangs' => function ($q) {
                $q->select('id_produk', 'id_gudang', 'stok_fisik')
                  ->with('gudang:id_gudang,nama_gudang,id_toko');
            },
            'kategori:id_kategori,nama_kategori',
            'satuanKecil:id_satuan,nama_satuan',
            'satuanBesar:id_satuan,nama_satuan'
        ])
            ->withSum(['stokGudangs as total_stok_gudang' => function ($q) use ($selectedTokoId) {
                $q->whereHas('gudang', function ($gq) use ($selectedTokoId) {
                    $gq->where('id_toko', $selectedTokoId);
                });
            }], 'stok_fisik')
            ->paginate(10)
            ->withQueryString();

        return view('owner.produk.index', compact('toko', 'selectedToko', 'allTokos', 'produks'));
    }

    public function create($id_toko)
    {
        $toko = Toko::findOrFail($id_toko);
        $kategoris = Kategori::select('id_kategori', 'nama_kategori')->orderBy('nama_kategori')->get();
        $satuans = Satuan::select('id_satuan', 'nama_satuan', 'tipe')->orderBy('tipe')->orderBy('nama_satuan')->get()->groupBy('tipe');
        $gudangs = Gudang::where('id_toko', $id_toko)->select('id_gudang', 'nama_gudang')->get();
        return view('owner.produk.create', compact('toko', 'kategoris', 'satuans', 'gudangs'));
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
            'stok_awal'         => 'required|integer|min:1',
            'lokasi_stok_awal'  => 'nullable|string', // 'toko' or id_gudang
            'gambar_produk'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->except(['gambar_produk', 'stok_awal', 'lokasi_stok_awal', '_token']);
            $data['is_active'] = $request->has('is_active') ? 1 : 0;

            // Auto-generate SKU if empty
            if (empty($data['sku'])) {
                $data['sku'] = 'PROD-' . now()->format('ymd') . '-' . strtoupper(substr(uniqid(), -6));
            }

            if ($request->hasFile('gambar_produk')) {
                $data['gambar_produk'] = $request->file('gambar_produk')->store('produk', 'public');
            }

            $produk = Produk::create($data);

            // Default Stok Toko (Storefront) entry is mandatory for product visibility
            $stokTokoEntry = [
                'id_toko'      => $toko->id_toko,
                'id_produk'    => $produk->id_produk,
                'stok_fisik'   => 0,
                'stok_minimal' => 5,
            ];

            if ($request->filled('stok_awal') && $request->stok_awal > 0) {
                $lokasi = $request->lokasi_stok_awal ?? 'toko';

                if ($lokasi === 'toko') {
                    // Masuk ke Stok Toko
                    $stokTokoEntry['stok_fisik'] = $request->stok_awal;
                    StokToko::create($stokTokoEntry);
                } else {
                    // Masuk ke Gudang (Pastikan Gudang milik Toko ini)
                    $gudangCheck = Gudang::where('id_toko', $toko->id_toko)->where('id_gudang', $lokasi)->exists();
                    
                    if ($gudangCheck) {
                        StokGudang::create([
                            'id_gudang'  => $lokasi,
                            'id_produk'  => $produk->id_produk,
                            'stok_fisik' => $request->stok_awal,
                        ]);
                        // Create empty tokostock
                        StokToko::create($stokTokoEntry);
                    } else {
                        // Fallback to toko if valid gudang not found (shouldn't happen usually)
                        $stokTokoEntry['stok_fisik'] = $request->stok_awal;
                        StokToko::create($stokTokoEntry);
                    }
                }
            } else {
                // No stock, just create empty record
                StokToko::create($stokTokoEntry);
            }

            DB::commit();
            
            

            Cache::forget("kasir_index_products_{$toko->id_toko}");
            Cache::forget("dashboard_owner_{$toko->id_toko}_" . date('Y-m-d'));
            
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
        $satuans = Satuan::select('id_satuan', 'nama_satuan', 'tipe')->orderBy('tipe')->orderBy('nama_satuan')->get()->groupBy('tipe');

        $stokToko = StokToko::where('id_toko', $toko->id_toko)
            ->where('id_produk', $produk->id_produk)
            ->first();

        $stokGudangs = StokGudang::with('gudang')
            ->where('id_produk', $produk->id_produk)
            ->whereHas('gudang', function($q) use ($toko) {
                $q->where('id_toko', $toko->id_toko);
            })
            ->get();

        return view('owner.produk.edit', compact('toko', 'produk', 'kategoris', 'satuans', 'stokToko', 'stokGudangs'));
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

            // Auto-generate SKU if empty
            if (empty($data['sku'])) {
                $data['sku'] = 'PROD-' . now()->format('ymd') . '-' . strtoupper(substr(uniqid(), -6));
            }

            if ($request->hasFile('gambar_produk')) {
                if ($produk->gambar_produk && Storage::disk('public')->exists($produk->gambar_produk)) {
                    Storage::disk('public')->delete($produk->gambar_produk);
                }
                $data['gambar_produk'] = $request->file('gambar_produk')->store('produk', 'public');
            }

            $produk->update($data);

            DB::commit();
            
            

            Cache::forget("kasir_index_products_{$toko->id_toko}");
            Cache::forget("dashboard_owner_{$toko->id_toko}_" . date('Y-m-d'));
            
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
        
        

        Cache::forget("kasir_index_products_{$toko->id_toko}");
        Cache::forget("dashboard_owner_{$toko->id_toko}_" . date('Y-m-d'));
        
        return redirect()->route('owner.toko.produk.index', $toko->id_toko)
            ->with('success', 'Produk dihapus');
    }
}
