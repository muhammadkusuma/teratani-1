<?php
namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Satuan;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    /**
     * Menampilkan daftar produk pada toko yang dipilih.
     */
    public function index(Toko $toko)
    {
        // Ambil produk yang hanya milik toko ini
        // Asumsi: Table produk punya kolom 'toko_id'
        $produks = $toko->produks()->latest()->paginate(10);

        return view('owner.produk.index', compact('toko', 'produks'));
    }

    /**
     * Form tambah produk baru untuk toko ini.
     */
    public function create(Toko $toko)
    {
        $kategoris = Kategori::all(); // Sesuaikan jika kategori per toko
        $satuans   = Satuan::all();

        return view('owner.produk.create', compact('toko', 'kategoris', 'satuans'));
    }

    /**
     * Simpan produk baru ke database.
     */
    public function store(Request $request, Toko $toko)
    {
        $validated = $request->validate([
            'nama'        => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'satuan_id'   => 'required|exists:satuans,id',
            'harga_beli'  => 'required|numeric|min:0',
            'harga_jual'  => 'required|numeric|min:0',
            'stok'        => 'required|integer|min:0',
            'foto'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'deskripsi'   => 'nullable|string',
        ]);

        // Handle Upload Foto
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('produk-images', 'public');
        }

        // Simpan via relasi toko agar toko_id otomatis terisi
        $toko->produks()->create($validated);

        return redirect()->route('owner.toko.produk.index', $toko->id)
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Form edit produk.
     */
    public function edit(Toko $toko, Produk $produk)
    {
        // Pastikan produk milik toko ini (Security Check)
        if ($produk->toko_id !== $toko->id) {
            abort(403);
        }

        $kategoris = Kategori::all();
        $satuans   = Satuan::all();

        return view('owner.produk.edit', compact('toko', 'produk', 'kategoris', 'satuans'));
    }

    /**
     * Update produk.
     */
    public function update(Request $request, Toko $toko, Produk $produk)
    {
        if ($produk->toko_id !== $toko->id) {
            abort(403);
        }

        $validated = $request->validate([
            'nama'        => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'satuan_id'   => 'required|exists:satuans,id',
            'harga_beli'  => 'required|numeric|min:0',
            'harga_jual'  => 'required|numeric|min:0',
            'stok'        => 'required|integer|min:0',
            'foto'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'deskripsi'   => 'nullable|string',
        ]);

        // Handle Ganti Foto
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($produk->foto) {
                Storage::disk('public')->delete($produk->foto);
            }
            $validated['foto'] = $request->file('foto')->store('produk-images', 'public');
        }

        $produk->update($validated);

        return redirect()->route('owner.toko.produk.index', $toko->id)
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Hapus produk.
     */
    public function destroy(Toko $toko, Produk $produk)
    {
        if ($produk->toko_id !== $toko->id) {
            abort(403);
        }

        if ($produk->foto) {
            Storage::disk('public')->delete($produk->foto);
        }

        $produk->delete();

        return redirect()->route('owner.toko.produk.index', $toko->id)
            ->with('success', 'Produk berhasil dihapus.');
    }
}
