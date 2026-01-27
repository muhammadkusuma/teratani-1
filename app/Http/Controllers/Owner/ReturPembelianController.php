<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\ReturPembelian;
use App\Models\ReturPembelianDetail;
use App\Models\Produk;
use App\Models\StokGudang;
use App\Models\Gudang;
use App\Models\Distributor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturPembelianController extends Controller
{
    public function index(Request $request)
    {
        $id_perusahaan = auth()->user()->id_perusahaan;
        
        // Fetch all Tokos for the company
        $tokos = \App\Models\Toko::where('id_perusahaan', $id_perusahaan)->get();
        $tokoIds = $tokos->pluck('id_toko');

        // Fetch all Gudangs for the company
        $gudangs = Gudang::whereIn('id_toko', $tokoIds)->get();
        
        // Start Query - Scoped to Company via Gudang
        $query = ReturPembelian::with(['distributor', 'gudang.toko'])
            ->whereHas('gudang', function($q) use ($tokoIds) {
                $q->whereIn('id_toko', $tokoIds);
            });

        // Filtering Logic
        if ($request->filled('id_toko')) {
            $query->whereHas('gudang', function($q) use ($request) {
                $q->where('id_toko', $request->id_toko);
            });
        }
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tgl_retur', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tgl_retur', '<=', $request->tanggal_sampai);
        }
        if ($request->filled('id_distributor')) {
            $query->where('id_distributor', $request->id_distributor);
        }
        if ($request->filled('id_gudang')) {
            $query->where('id_gudang', $request->id_gudang);
        }

        $returs = $query->latest()->paginate(15);

        // Statistics
        $today = now()->format('Y-m-d');
        $thisMonth = now()->month;
        $thisYear = now()->year;

        // Base Summary Query (Scoped to Company)
        $baseSummaryQuery = ReturPembelian::whereHas('gudang', function($q) use ($tokoIds) {
             $q->whereIn('id_toko', $tokoIds);
        });
        
        // Apply Structural Filters to Base Summary (Toko, Distributor, Gudang)
        if ($request->filled('id_toko')) {
            $baseSummaryQuery->whereHas('gudang', function($q) use ($request) {
                $q->where('id_toko', $request->id_toko);
            });
        }
        if ($request->filled('id_distributor')) {
            $baseSummaryQuery->where('id_distributor', $request->id_distributor);
        }
        if ($request->filled('id_gudang')) {
            $baseSummaryQuery->where('id_gudang', $request->id_gudang);
        }

        $filteredSummaryQuery = clone $query; // Obey all filters including dates

        $summary = [
            'total_retur' => $filteredSummaryQuery->sum('total_retur'),
            'jumlah_transaksi' => $filteredSummaryQuery->count(),
            
            'hari_ini' => (clone $baseSummaryQuery)->whereDate('tgl_retur', $today)->sum('total_retur'),
            'bulan_ini' => (clone $baseSummaryQuery)->whereMonth('tgl_retur', $thisMonth)->whereYear('tgl_retur', $thisYear)->sum('total_retur'),
            'tahun_ini' => (clone $baseSummaryQuery)->whereYear('tgl_retur', $thisYear)->sum('total_retur'),
        ];
        
        $distributors = Distributor::all();

        return view('owner.retur-pembelian.index', compact('returs', 'summary', 'distributors', 'gudangs', 'tokos'));
    }

    public function create()
    {
        $id_perusahaan = auth()->user()->id_perusahaan;
        $tokoIds = \App\Models\Toko::where('id_perusahaan', $id_perusahaan)->pluck('id_toko');
        $tokos = \App\Models\Toko::where('id_perusahaan', $id_perusahaan)->get();

        $gudangs = Gudang::with('toko')->whereIn('id_toko', $tokoIds)->get();
        $gudangIds = $gudangs->pluck('id_gudang');

        $distributors = Distributor::all(); 
        
        // Fetch Stock for these warehouses
        $stokGudangs = StokGudang::with(['produk', 'gudang.toko'])
            ->whereIn('id_gudang', $gudangIds)
            ->where('stok_fisik', '>', 0)
            ->get();
            
        // Fetch Stock for Shops (Direct Store Stock)
        $stokTokos = \App\Models\StokToko::with(['produk', 'toko'])
            ->whereIn('id_toko', $tokoIds)
            ->where('stok_fisik', '>', 0)
            ->get();

        // Merge stocks for JS usage if needed, or pass separately. passing separately is safer.
        return view('owner.retur-pembelian.create', compact('gudangs', 'distributors', 'stokGudangs', 'tokos', 'stokTokos'));
    }

    public function store(Request $request)
    {
        // Parse source_id to determine if it's from Gudang or Toko
        if ($request->has('source_id')) {
            $parts = explode('_', $request->source_id);
            if (count($parts) == 2) {
                if ($parts[0] == 'gudang') {
                    $request->merge(['id_gudang' => $parts[1]]);
                } elseif ($parts[0] == 'toko') {
                    $request->merge(['id_toko' => $parts[1]]);
                }
            }
        }

        $request->validate([
            'id_distributor' => 'required|exists:distributor,id_distributor',
            'id_gudang' => 'nullable|required_without:id_toko|exists:gudang,id_gudang', 
            'id_toko' => 'nullable|required_without:id_gudang|exists:toko,id_toko',
            'tgl_retur' => 'required|date',
            'produk_id' => 'required|array',
            'qty' => 'required|array',
            'harga_satuan' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            $retur = ReturPembelian::create([
                'id_distributor' => $request->id_distributor,
                'id_gudang' => $request->id_gudang,
                'id_toko' => $request->id_toko, // Helper for Shop Stock returns
                'tgl_retur' => $request->tgl_retur,
                'keterangan' => $request->keterangan,
            ]);

            $totalRetur = 0;

            foreach ($request->produk_id as $key => $id_produk) {
                $qty = $request->qty[$key];
                $harga = $request->harga_satuan[$key];
                $subtotal = $qty * $harga;

                if ($qty > 0) {
                    ReturPembelianDetail::create([
                        'id_retur_pembelian' => $retur->id_retur_pembelian,
                        'id_produk' => $id_produk,
                        'qty' => $qty,
                        'harga_satuan' => $harga,
                        'subtotal' => $subtotal,
                    ]);

                    $totalRetur += $subtotal;

                    // Decrease Stock logic
                    if ($request->id_gudang) {
                        // Warehouse Stock
                        $stokGudang = StokGudang::with('produk')
                                                ->where('id_gudang', $request->id_gudang)
                                                ->where('id_produk', $id_produk)
                                                ->first();
                        
                        if ($stokGudang) {
                            if ($stokGudang->stok_fisik >= $qty) {
                                $stokGudang->decrement('stok_fisik', $qty);
                            } else {
                                $namaProduk = $stokGudang->produk->nama_produk ?? "ID: $id_produk";
                                throw new \Exception("Stok gudang tidak cukup untuk produk: $namaProduk. Stok saat ini: " . $stokGudang->stok_fisik);
                            }
                        } else {
                            $prod = Produk::find($id_produk);
                            $namaProduk = $prod->nama_produk ?? "ID: $id_produk";
                            throw new \Exception("Stok tidak ditemukan di gudang ini untuk produk: $namaProduk");
                        }
                    } elseif ($request->id_toko) {
                        // Shop Stock
                        $stokToko = \App\Models\StokToko::with('produk')
                            ->where('id_toko', $request->id_toko)
                            ->where('id_produk', $id_produk)
                            ->first();

                        if ($stokToko) {
                            if ($stokToko->stok_fisik >= $qty) {
                                $stokToko->decrement('stok_fisik', $qty);
                            } else {
                                $namaProduk = $stokToko->produk->nama_produk ?? "ID: $id_produk";
                                throw new \Exception("Stok toko tidak cukup untuk produk: $namaProduk. Stok saat ini: " . $stokToko->stok_fisik);
                            }
                        } else {
                            $prod = Produk::find($id_produk);
                            $namaProduk = $prod->nama_produk ?? "ID: $id_produk";
                            throw new \Exception("Stok tidak ditemukan di toko ini untuk produk: $namaProduk");
                        }
                    }
                }
            }

            $retur->update(['total_retur' => $totalRetur]);

            DB::commit();
            return redirect()->route('owner.retur-pembelian.index')->with('success', 'Retur Pembelian berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $retur = ReturPembelian::with(['details.produk', 'distributor', 'gudang', 'toko'])->findOrFail($id);
        return view('owner.retur-pembelian.show', compact('retur'));
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $retur = ReturPembelian::with('details')->findOrFail($id);

            // Restore Stock
            foreach ($retur->details as $detail) {
                if ($retur->id_gudang) {
                    $stokGudang = StokGudang::where('id_gudang', $retur->id_gudang)
                                            ->where('id_produk', $detail->id_produk)
                                            ->first();
                    if ($stokGudang) {
                        $stokGudang->increment('stok_fisik', $detail->qty);
                    }
                } elseif ($retur->id_toko) {
                     $stokToko = \App\Models\StokToko::where('id_toko', $retur->id_toko)
                                            ->where('id_produk', $detail->id_produk)
                                            ->first();
                    if ($stokToko) {
                        $stokToko->increment('stok_fisik', $detail->qty);
                    }
                }
            }

            $retur->details()->delete(); // Delete details first if no cascade
            $retur->delete();

            DB::commit();
            return redirect()->route('owner.retur-pembelian.index')->with('success', 'Data retur berhasil dihapus dan stok dikembalikan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
