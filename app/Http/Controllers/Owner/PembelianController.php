<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Distributor;
use App\Models\Gudang;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Produk;
use App\Models\RiwayatStok;
use App\Models\StokGudang;
use App\Models\StokToko;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PembelianController extends Controller
{
    public function index(Request $request, $id_toko)
    {
        $toko = Toko::findOrFail($id_toko);
        $query = Pembelian::with('distributor')->orderBy('tanggal', 'desc');

        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        $pembelians = $query->paginate(15)->withQueryString();

        return view('owner.pembelian.index', compact('toko', 'pembelians'));
    }

    public function create($id_toko)
    {
        $toko = Toko::findOrFail($id_toko);
        $distributors = Distributor::where('id_toko', $id_toko)->active()->get();
        // Gudang now belongs to Toko directly
        $gudangs = Gudang::where('id_toko', $id_toko)->get();
        $produks = Produk::whereHas('stokTokos', function($q) use ($id_toko) {
            $q->where('id_toko', $id_toko);
        })->get();

        return view('owner.pembelian.create', compact('toko', 'distributors', 'gudangs', 'produks'));
    }

    public function store(Request $request, $id_toko)
    {
        $toko = Toko::findOrFail($id_toko);

        $request->validate([
            'id_distributor' => 'required|exists:distributor,id_distributor',
            'tanggal' => 'required|date',
            'no_faktur' => 'nullable|string|max:50',
            'keterangan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.id_produk' => 'required|exists:produk,id_produk',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga_satuan' => 'required|integer|min:0',
            'destination_type' => 'required|in:toko,gudang',
            'destination_id' => 'required', // Will validate manually based on type
        ]);

        // Validate destination
        if ($request->destination_type === 'gudang') {
            $exists = Gudang::where('id_gudang', $request->destination_id)->exists();
            if (!$exists) return back()->withErrors(['destination_id' => 'Gudang tidak valid.']);
        } else {
             if ($request->destination_id != $id_toko) {
                 return back()->withErrors(['destination_id' => 'Toko tidak valid.']);
             }
        }

        DB::beginTransaction();
        try {
            $total = 0;
            foreach ($request->items as $item) {
                $total += $item['jumlah'] * $item['harga_satuan'];
            }

            $pembelian = Pembelian::create([
                'id_distributor' => $request->id_distributor,
                'no_faktur' => $request->no_faktur,
                'tanggal' => $request->tanggal,
                'total' => $total,
                'keterangan' => $request->keterangan,
            ]);

            foreach ($request->items as $item) {
                $subtotal = $item['jumlah'] * $item['harga_satuan'];
                
                PembelianDetail::create([
                    'id_pembelian' => $pembelian->id_pembelian,
                    'id_produk' => $item['id_produk'],
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $item['harga_satuan'],
                    'total_harga' => $subtotal,
                ]);

                // Stock Update & Logging
                if ($request->destination_type === 'gudang') {
                    // Update Gudang Stock
                    $stokGudang = StokGudang::firstOrCreate(
                        ['id_gudang' => $request->destination_id, 'id_produk' => $item['id_produk']],
                        ['stok_fisik' => 0]
                    );
                    $stokGudang->increment('stok_fisik', $item['jumlah']);
                    $stokAkhir = $stokGudang->stok_fisik;

                    // Log History
                    RiwayatStok::create([
                        'id_produk' => $item['id_produk'],
                        'id_gudang' => $request->destination_id,
                        'jenis' => 'masuk',
                        'jumlah' => $item['jumlah'],
                        'stok_akhir' => $stokAkhir,
                        'keterangan' => 'Pembelian dari Distributor',
                        'referensi' => 'PEMBELIAN-' . $pembelian->id_pembelian,
                        'tanggal' => $request->tanggal,
                    ]);

                } else {
                    // Update Toko Stock
                    $stokToko = StokToko::firstOrCreate(
                        ['id_toko' => $id_toko, 'id_produk' => $item['id_produk']],
                        ['stok_fisik' => 0, 'stok_minimal' => 5]
                    );
                    $stokToko->increment('stok_fisik', $item['jumlah']);
                    $stokAkhir = $stokToko->stok_fisik;

                    // Log History
                    RiwayatStok::create([
                        'id_produk' => $item['id_produk'],
                        'id_toko' => $id_toko,
                        'jenis' => 'masuk',
                        'jumlah' => $item['jumlah'],
                        'stok_akhir' => $stokAkhir,
                        'keterangan' => 'Pembelian dari Distributor',
                        'referensi' => 'PEMBELIAN-' . $pembelian->id_pembelian,
                        'tanggal' => $request->tanggal,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('owner.pembelian.index', $id_toko)->with('success', 'Pembelian berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan pembelian: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id_toko, $id_pembelian)
    {
        $toko = Toko::findOrFail($id_toko);
        $pembelian = Pembelian::with(['distributor', 'details.produk'])->findOrFail($id_pembelian);
        return view('owner.pembelian.show', compact('toko', 'pembelian'));
    }
}
