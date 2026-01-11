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
use Illuminate\Support\Facades\Log;

class MutasiController extends Controller
{
    /**
     * Helper protected untuk mendapatkan ID Tenant yang aktif.
     * Digunakan di semua fungsi CRUD dan AJAX agar konsisten.
     */
    protected function getActiveTenantId()
    {
        if (! Auth::check()) {
            return null;
        }

        $user = Auth::user();

        // 1. Coba ambil properti id_tenant langsung dari tabel users (jika ada kolomnya)
        if (! empty($user->id_tenant)) {
            return $user->id_tenant;
        }

        // 2. Jika tidak ada, ambil dari relasi tenants (ambil yang pertama/primary)
        // Pastikan relasi 'tenants' ada di model User
        if (method_exists($user, 'tenants')) {
            $tenant = $user->tenants()->first();
            if ($tenant) {
                return $tenant->id_tenant;
            }
        }

        // 3. Fallback (Hanya untuk dev/debug atau default tenant)
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
        $tokos    = Toko::where('id_tenant', $idTenant)->get();

        return view('owner.mutasi.create', compact('tokos'));
    }

    /**
     * AJAX Route: Mengambil daftar produk beserta stok di toko tertentu
     */
    /**
     * AJAX Route: Mengambil daftar produk di toko tertentu
     * PERBAIKAN: Hanya menampilkan produk yang stok fisiknya > 0
     */
    public function getProdukByToko($id_toko)
    {
        try {
            // 1. Pastikan user login
            if (! Auth::check()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // 2. Ambil Tenant ID
            $idTenant = $this->getActiveTenantId();
            if (! $idTenant) {
                return response()->json(['error' => 'Tenant not found'], 404);
            }

            // 3. Validasi Toko
            $cekToko = Toko::where('id_toko', $id_toko)
                ->where('id_tenant', $idTenant)
                ->exists();

            if (! $cekToko) {
                return response()->json([]);
            }

            // 4. Query Produk (Hanya yang memiliki stok > 0)
            $produks = DB::table('produk')
            // Gunakan JOIN (Inner Join) bukan LEFT JOIN karena kita hanya mau data yang ada di tabel stok
                ->join('stok_toko', 'produk.id_produk', '=', 'stok_toko.id_produk')
                ->where('stok_toko.id_toko', $id_toko)  // Filter khusus toko yang dipilih
                ->where('produk.id_tenant', $idTenant)  // Pastikan produk milik tenant ini
                ->where('produk.is_active', true)       // Pastikan produk aktif
                ->where('stok_toko.stok_fisik', '>', 0) // <-- FILTER UTAMA: Stok harus lebih dari 0
                ->select(
                    'produk.id_produk',
                    'produk.nama_produk',
                    'produk.sku',
                    'stok_toko.stok_fisik'
                )
                ->orderBy('produk.nama_produk', 'asc')
                ->get();

            return response()->json($produks);

        } catch (\Exception $e) {
            Log::error("Error getProdukByToko: " . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan server',
                'debug'   => config('app.debug') ? $e->getMessage() : null,
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
                        ->lockForUpdate()
                        ->first();

                    if (! $stokAsal || $stokAsal->stok_fisik < $item['qty']) {
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
                    'status'           => 'Proses',
                    'keterangan'       => $request->keterangan,
                    'id_user_pengirim' => Auth::id(),
                ]);

                // 3. Simpan Detail & Kurangi Stok
                foreach ($request->items as $item) {
                    MutasiDetail::create([
                        'id_mutasi'  => $mutasi->id_mutasi,
                        'id_produk'  => $item['id_produk'],
                        'qty_kirim'  => $item['qty'],
                        'qty_terima' => 0,
                    ]);

                    $stokAsal = StokToko::where('id_toko', $request->id_toko_asal)
                        ->where('id_produk', $item['id_produk'])
                        ->first();

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
                $mutasi = MutasiStok::with('details')
                    ->where('id_tenant', $idTenant)
                    ->where('status', 'Proses')
                    ->lockForUpdate()
                    ->findOrFail($id);

                $mutasi->update([
                    'status'           => 'Diterima',
                    'tgl_terima'       => now(),
                    'id_user_penerima' => Auth::id(),
                ]);

                foreach ($mutasi->details as $detail) {
                    $detail->update(['qty_terima' => $detail->qty_kirim]);

                    $stokTujuan = StokToko::firstOrCreate(
                        [
                            'id_toko'   => $mutasi->id_toko_tujuan,
                            'id_produk' => $detail->id_produk,
                        ],
                        [
                            'stok_fisik'   => 0,
                            'stok_minimal' => 5,
                        ]
                    );

                    $stokTujuan->increment('stok_fisik', $detail->qty_kirim);
                }
            });

            return redirect()->route('owner.mutasi.index')->with('success', 'Barang berhasil diterima & Stok tujuan bertambah!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menerima mutasi: ' . $e->getMessage());
        }
    }
}
