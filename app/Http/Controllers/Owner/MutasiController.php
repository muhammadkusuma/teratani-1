<?php
namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\MutasiDetail;
use App\Models\MutasiStok;
use App\Models\StokToko;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MutasiController extends Controller
{
    // Helper private untuk mendapatkan ID Tenant yang aktif
    private function getActiveTenantId()
    {
        $user = Auth::user();

        // 1. Coba ambil properti id_tenant langsung dari tabel users
        if (! empty($user->id_tenant)) {
            return $user->id_tenant;
        }

        // 2. Jika tidak ada, ambil dari relasi tenants (ambil yang pertama/primary)
        // Karena di User.php relasinya belongsToMany
        $tenant = $user->tenants()->first();

        if ($tenant) {
            return $tenant->id_tenant;
        }

        // 3. Fallback (Hanya untuk dev/debug, kembalikan 1 atau null)
        return 1;
    }

    public function index()
    {
        $idTenant = $this->getActiveTenantId();

        $mutasi = MutasiStok::with(['tokoAsal', 'tokoTujuan', 'pengirim'])
            ->where('id_tenant', $idTenant)
            ->orderBy('tgl_kirim', 'desc')
            ->paginate(10);

        return view('owner.mutasi.index', compact('mutasi'));
    }

    public function create()
    {
        $idTenant = $this->getActiveTenantId();

        // Debugging: Uncomment baris di bawah ini jika masih kosong untuk melihat ID tenant yang didapat
        // dd($idTenant);

        // Ambil daftar toko berdasarkan tenant yang valid
        $tokos = Toko::where('id_tenant', $idTenant)->get();

        // Debugging: Pastikan ada data tokonya
        // if($tokos->isEmpty()) { dd("Data Toko Kosong untuk Tenant ID: " . $idTenant); }

        return view('owner.mutasi.create', compact('tokos'));
    }

    // Ganti method getProdukByToko dengan versi debug ini
    public function getProdukByToko($id_toko)
    {
        try {
            $user = Auth::user();

            // 1. Ambil Tenant ID (Logika deteksi tenant yang aman)
            $idTenant = $user->id_tenant;
            if (empty($idTenant)) {
                if (method_exists($user, 'tenants')) {
                    $tenantRelasi = $user->tenants()->first();
                    $idTenant     = $tenantRelasi ? $tenantRelasi->id_tenant : 1;
                } else {
                    $idTenant = 1;
                }
            }

            // 2. Validasi Toko
            $cekToko = Toko::where('id_toko', $id_toko)
                ->where('id_tenant', $idTenant)
                ->exists();

            if (! $cekToko) {
                return response()->json([]);
            }

            // 3. Query Produk (PERBAIKAN: Ganti 'kode_produk' jadi 'sku')
            $produks = DB::table('produk')
                ->leftJoin('stok_toko', function ($join) use ($id_toko) {
                    $join->on('produk.id_produk', '=', 'stok_toko.id_produk')
                        ->where('stok_toko.id_toko', '=', $id_toko);
                })
                ->where('produk.id_tenant', $idTenant)
                ->select(
                    'produk.id_produk',
                    'produk.nama_produk',
                    'produk.sku', // <-- INI YANG DIUBAH (sebelumnya kode_produk)
                    DB::raw('COALESCE(stok_toko.stok_fisik, 0) as stok_fisik')
                )
                ->get();

            return response()->json($produks);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $idTenant = $this->getActiveTenantId();

        $request->validate([
            'id_toko_asal'      => 'required|different:id_toko_tujuan',
            'id_toko_tujuan'    => 'required',
            'tgl_kirim'         => 'required|date',
            'items'             => 'required|array|min:1',
            'items.*.id_produk' => 'required',
            'items.*.qty'       => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request, $idTenant) {
            // 1. Buat Header Mutasi
            $mutasi = MutasiStok::create([
                'id_tenant'        => $idTenant,
                'no_mutasi'        => 'TRF-' . time(),
                'id_toko_asal'     => $request->id_toko_asal,
                'id_toko_tujuan'   => $request->id_toko_tujuan,
                'tgl_kirim'        => $request->tgl_kirim,
                'status'           => 'Proses', // Barang masih di jalan (Transit)
                'keterangan'       => $request->keterangan,
                'id_user_pengirim' => Auth::id(),
            ]);

            foreach ($request->items as $item) {
                // 2. Simpan Detail
                MutasiDetail::create([
                    'id_mutasi'  => $mutasi->id_mutasi,
                    'id_produk'  => $item['id_produk'],
                    'qty_kirim'  => $item['qty'],
                    'qty_terima' => 0, // Belum diterima
                ]);

                // 3. PENGIRIMAN: Kurangi Stok Toko ASAL
                $stokAsal = StokToko::where('id_toko', $request->id_toko_asal)
                    ->where('id_produk', $item['id_produk'])
                    ->first();

                if ($stokAsal) {
                    $stokAsal->decrement('stok_fisik', $item['qty']);
                } else {
                    // Handle jika stok minus (opsional)
                    StokToko::create([
                        'id_toko'    => $request->id_toko_asal,
                        'id_produk'  => $item['id_produk'],
                        'stok_fisik' => -($item['qty']),
                        // Pastikan kolom id_tenant ada di tabel stok_toko jika struktur DB memintanya
                    ]);
                }
            }
        });

        return redirect()->route('owner.mutasi.index')->with('success', 'Transfer stok berhasil dibuat! Menunggu konfirmasi penerimaan.');
    }

    public function show($id)
    {
        $idTenant = $this->getActiveTenantId();

        $mutasi = MutasiStok::with(['tokoAsal', 'tokoTujuan', 'pengirim', 'details.produk'])
            ->where('id_tenant', $idTenant)
            ->findOrFail($id);

        return view('owner.mutasi.show', compact('mutasi'));
    }

    public function terima($id)
    {
        $idTenant = $this->getActiveTenantId();

        DB::transaction(function () use ($id, $idTenant) {
            // Ambil data mutasi beserta detailnya
            $mutasi = MutasiStok::with('details')
                ->where('id_tenant', $idTenant)
                ->where('status', 'Proses') // Hanya bisa terima jika status Proses
                ->lockForUpdate()           // Kunci row agar tidak double submit
                ->findOrFail($id);

            // Update Header
            $mutasi->update([
                'status'           => 'Selesai',
                'tgl_diterima'     => now(),
                'id_user_penerima' => Auth::id(),
            ]);

            foreach ($mutasi->details as $detail) {
                // 1. Update Qty Terima di tabel detail (Asumsi terima semua sesuai kiriman)
                // Jika ingin parsial, logic ini harus diubah menerima input dari user
                $detail->update([
                    'qty_terima' => $detail->qty_kirim,
                ]);

                // 2. PENERIMAAN: Tambah Stok Toko TUJUAN
                $stokTujuan = StokToko::where('id_toko', $mutasi->id_toko_tujuan)
                    ->where('id_produk', $detail->id_produk)
                    ->first();

                if ($stokTujuan) {
                    $stokTujuan->increment('stok_fisik', $detail->qty_kirim);
                } else {
                    // Jika produk belum ada di toko tujuan, buat baris baru
                    StokToko::create([
                        'id_toko'    => $mutasi->id_toko_tujuan,
                        'id_produk'  => $detail->id_produk,
                        'stok_fisik' => $detail->qty_kirim,
                    ]);
                }
            }
        });

        return redirect()->route('owner.mutasi.index')->with('success', 'Barang berhasil diterima & Stok tujuan bertambah!');
    }
}
