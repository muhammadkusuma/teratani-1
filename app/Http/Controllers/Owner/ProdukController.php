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

class ProdukController extends Controller
{
    public function index(Toko $toko)
    {
        // Ambil produk milik tenant yang sama dengan toko ini
        // Load relasi stokToko khusus untuk toko ini saja
        $produks = $toko->produks()
            ->with(['kategori', 'satuanKecil']) // Perbaikan nama relasi
            ->with(['stokTokos' => function ($query) use ($toko) {
                $query->where('id_toko', $toko->getKey());
            }])
            ->latest()
            ->paginate(10);

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
        // 1. Validasi Input (Sesuai nama field di Form)
        $validated = $request->validate([
            'nama'        => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id_kategori', // Sesuaikan nama tabel
            'satuan_id'   => 'required|exists:satuan,id_satuan',
            'harga_beli'  => 'required|numeric|min:0',
            'harga_jual'  => 'required|numeric|min:0',
            'stok'        => 'required|integer|min:0',
            'foto'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'deskripsi'   => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $toko, $validated) {
            // Handle Upload Foto
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('produk-images', 'public');
            }

            // 2. Mapping Data untuk Tabel Produk (Sesuaikan nama kolom DB)
            $dataProduk = [
                // id_tenant otomatis terisi lewat relasi $toko->produks()->create()
                'nama_produk'          => $validated['nama'],
                'id_kategori'          => $validated['kategori_id'],
                'id_satuan_kecil'      => $validated['satuan_id'], // Asumsi satuan utama = satuan kecil
                'harga_beli_rata_rata' => $validated['harga_beli'],
                'harga_jual_umum'      => $validated['harga_jual'],
                'deskripsi'            => $validated['deskripsi'],
                'gambar_produk'        => $fotoPath,
                'is_active'            => true,
            ];

            // Simpan Master Produk
            $produk = $toko->produks()->create($dataProduk);

            // 3. Simpan Stok Awal di StokToko
            StokToko::create([
                'id_toko'      => $toko->getKey(),
                'id_produk'    => $produk->getKey(),
                'stok_fisik'   => $validated['stok'],
                'stok_minimal' => 5,
            ]);
        });

        return redirect()->route('owner.toko.produk.index', $toko->getKey())
            ->with('success', 'Produk dan stok awal berhasil ditambahkan.');
    }

    public function edit(Toko $toko, Produk $produk)
    {
        // Security: Pastikan produk milik tenant yang sama
        if ($produk->id_tenant !== $toko->id_tenant) {
            abort(403);
        }

        // Ambil stok di toko ini
        $stokToko = StokToko::where('id_toko', $toko->getKey())
            ->where('id_produk', $produk->getKey())
            ->first();

        // Inject nilai stok ke object produk untuk ditampilkan di form
        $produk->stok_saat_ini = $stokToko ? $stokToko->stok_fisik : 0;

        $kategoris = Kategori::where('id_tenant', $toko->id_tenant)->get();
        $satuans   = Satuan::where('id_tenant', $toko->id_tenant)->get();

        return view('owner.produk.edit', compact('toko', 'produk', 'kategoris', 'satuans'));
    }

    public function update(Request $request, Toko $toko, Produk $produk)
    {
        if ($produk->id_tenant !== $toko->id_tenant) {
            abort(403);
        }

        $validated = $request->validate([
            'nama'        => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id_kategori',
            'satuan_id'   => 'required|exists:satuan,id_satuan',
            'harga_beli'  => 'required|numeric|min:0',
            'harga_jual'  => 'required|numeric|min:0',
            'stok'        => 'required|integer|min:0',
            'foto'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'deskripsi'   => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $toko, $produk, $validated) {
            // Handle Foto
            $fotoPath = $produk->gambar_produk;
            if ($request->hasFile('foto')) {
                if ($produk->gambar_produk) {
                    Storage::disk('public')->delete($produk->gambar_produk);
                }
                $fotoPath = $request->file('foto')->store('produk-images', 'public');
            }

            // Update Master Produk
            $produk->update([
                'nama_produk'          => $validated['nama'],
                'id_kategori'          => $validated['kategori_id'],
                'id_satuan_kecil'      => $validated['satuan_id'],
                'harga_beli_rata_rata' => $validated['harga_beli'],
                'harga_jual_umum'      => $validated['harga_jual'],
                'deskripsi'            => $validated['deskripsi'],
                'gambar_produk'        => $fotoPath,
            ]);

            // Update Stok Toko
            StokToko::updateOrCreate(
                [
                    'id_toko'   => $toko->getKey(),
                    'id_produk' => $produk->getKey(),
                ],
                [
                    'stok_fisik' => $validated['stok'],
                ]
            );
        });

        return redirect()->route('owner.toko.produk.index', $toko->getKey())
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Toko $toko, Produk $produk)
    {
        if ($produk->id_tenant !== $toko->id_tenant) {
            abort(403);
        }

        if ($produk->gambar_produk) {
            Storage::disk('public')->delete($produk->gambar_produk);
        }

        $produk->delete();

        return redirect()->route('owner.toko.produk.index', $toko->getKey())
            ->with('success', 'Produk berhasil dihapus.');
    }
}
