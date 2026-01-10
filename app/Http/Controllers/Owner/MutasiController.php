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

        try {
            DB::transaction(function () use ($request, $idTenant) {
                // 1. Validasi Stok Dulu (Cegah stok minus saat kirim)
                foreach ($request->items as $item) {
                    $stokAsal = StokToko::where('id_toko', $request->id_toko_asal)
                        ->where('id_produk', $item['id_produk'])
                        ->lockForUpdate() // Kunci row agar tidak ada transaksi lain yang mengubah saat pengecekan
                        ->first();

                    if (! $stokAsal || $stokAsal->stok_fisik < $item['qty']) {
                        // Cari nama produk untuk pesan error yang informatif (Opsional)
                        $namaProduk = DB::table('produk')->where('id_produk', $item['id_produk'])->value('nama_produk');
                        throw new \Exception("Stok tidak mencukupi untuk produk: " . ($namaProduk ?? 'ID ' . $item['id_produk']));
                    }
                }

                // 2. Buat Header Mutasi
                $mutasi = MutasiStok::create([
                    'id_tenant'        => $idTenant,
                    'no_mutasi'        => 'TRF-' . time(),
                    'id_toko_asal'     => $request->id_toko_asal,
                    'id_toko_tujuan'   => $request->id_toko_tujuan,
                    'tgl_kirim'        => $request->tgl_kirim,
                    'status'           => 'Proses', // Sesuai migration enum
                    'keterangan'       => $request->keterangan,
                    'id_user_pengirim' => Auth::id(),
                ]);

                foreach ($request->items as $item) {
                    // 3. Simpan Detail
                    MutasiDetail::create([
                        'id_mutasi'  => $mutasi->id_mutasi,
                        'id_produk'  => $item['id_produk'],
                        'qty_kirim'  => $item['qty'],
                        'qty_terima' => 0,
                    ]);

                    // 4. Kurangi Stok Toko ASAL
                    $stokAsal = StokToko::where('id_toko', $request->id_toko_asal)
                        ->where('id_produk', $item['id_produk'])
                        ->first();

                    // Kita sudah validasi di atas, jadi aman untuk decrement
                    $stokAsal->decrement('stok_fisik', $item['qty']);
                }
            });

            return redirect()->route('owner.mutasi.index')->with('success', 'Transfer stok berhasil dibuat! Stok asal telah dikurangi.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses mutasi: ' . $e->getMessage())->withInput();
        }
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

        try {
            DB::transaction(function () use ($id, $idTenant) {
                // Ambil data mutasi beserta detailnya
                $mutasi = MutasiStok::with('details')
                    ->where('id_tenant', $idTenant)
                    ->where('status', 'Proses') // Pastikan hanya yang status 'Proses'
                    ->lockForUpdate()           // Kunci row
                    ->findOrFail($id);

                // Update Header
                // PERBAIKAN: Gunakan 'Diterima' sesuai file migration 2024_01_01_000003_create_inventory_tables.php
                $mutasi->update([
                    'status'           => 'Diterima',
                    'tgl_terima'       => now(), // Perbaikan nama kolom (sebelumnya tgl_diterima, di migration tgl_terima)
                    'id_user_penerima' => Auth::id(),
                ]);

                foreach ($mutasi->details as $detail) {
                    // 1. Update Qty Terima di tabel detail
                    $detail->update([
                        'qty_terima' => $detail->qty_kirim,
                    ]);

                    // 2. Tambah Stok Toko TUJUAN
                    // Cek apakah produk sudah ada di toko tujuan
                    $stokTujuan = StokToko::where('id_toko', $mutasi->id_toko_tujuan)
                        ->where('id_produk', $detail->id_produk)
                        ->first();

                    if ($stokTujuan) {
                        $stokTujuan->increment('stok_fisik', $detail->qty_kirim);
                    } else {
                        // Jika belum ada, buat record baru
                        StokToko::create([
                            'id_toko'      => $mutasi->id_toko_tujuan,
                            'id_produk'    => $detail->id_produk,
                            'stok_fisik'   => $detail->qty_kirim,
                            'stok_minimal' => 5, // Default value
                                                 // 'lokasi_rak' => null, // Default
                        ]);
                    }
                }
            });

            return redirect()->route('owner.mutasi.index')->with('success', 'Barang berhasil diterima & Stok tujuan bertambah!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menerima mutasi: ' . $e->getMessage());
        }
    }
}
