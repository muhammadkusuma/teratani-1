<?php
namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\MutasiDetail;
use App\Models\MutasiStok;
use App\Models\Produk;
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
        if (!empty($user->id_tenant)) {
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

    public function getProdukByToko($id_toko)
    {
        $idTenant = $this->getActiveTenantId();

        // Validasi: Pastikan toko milik tenant user ini
        $cekToko = Toko::where('id_toko', $id_toko)
            ->where('id_tenant', $idTenant)
            ->exists();

        if (!$cekToko) {
            return response()->json([]);
        }

        // Ambil produk JOIN dengan stok_toko agar bisa melihat stok fisik saat ini
        $produks = DB::table('produk')
            ->leftJoin('stok_toko', function($join) use ($id_toko) {
                $join->on('produk.id_produk', '=', 'stok_toko.id_produk')
                     ->where('stok_toko.id_toko', '=', $id_toko);
            })
            ->where('produk.id_tenant', $idTenant)
            ->select(
                'produk.id_produk', 
                'produk.nama_produk', 
                'produk.kode_produk', 
                // Coalesce agar jika null (belum ada record stok) dianggap 0
                DB::raw('COALESCE(stok_toko.stok_fisik, 0) as stok_fisik')
            )
            ->get();

        return response()->json($produks);
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
                'status'           => 'Proses',
                'keterangan'       => $request->keterangan,
                'id_user_pengirim' => Auth::id(),
            ]);

            foreach ($request->items as $item) {
                // 2. Simpan Detail Mutasi
                MutasiDetail::create([
                    'id_mutasi'  => $mutasi->id_mutasi,
                    'id_produk'  => $item['id_produk'],
                    'qty_kirim'  => $item['qty'],
                    'qty_terima' => 0, 
                ]);

                // 3. Kurangi Stok Fisik di Toko Asal
                $stokAsal = StokToko::where('id_toko', $request->id_toko_asal)
                    ->where('id_produk', $item['id_produk'])
                    ->first();

                if ($stokAsal) {
                    $stokAsal->decrement('stok_fisik', $item['qty']);
                } else {
                    StokToko::create([
                        'id_toko'    => $request->id_toko_asal,
                        'id_produk'  => $item['id_produk'],
                        'stok_fisik' => -($item['qty']),
                    ]);
                }
            }
        });

        return redirect()->route('owner.mutasi.index')->with('success', 'Transfer stok berhasil dibuat!');
    }
}