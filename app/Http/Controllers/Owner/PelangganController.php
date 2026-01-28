<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\UtangPiutangPelanggan;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PelangganController extends Controller
{
    private function getActiveTokoId()
    {
        return session('toko_active_id');
    }

    public function index(Request $request)
    {
        $id_toko = $this->getActiveTokoId();

        if (!$id_toko) {
            return redirect()->route('owner.toko.index')->with('warning', 'Silakan pilih Toko/Cabang terlebih dahulu.');
        }

        $query = Pelanggan::where('id_toko', $id_toko);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_pelanggan', 'like', "%{$search}%")
                    ->orWhere('no_hp', 'like', "%{$search}%")
                    ->orWhere('kode_pelanggan', 'like', "%{$search}%");
            });
        }

        $pelanggan = $query->latest()->paginate(10);

        return view('owner.pelanggan.index', compact('pelanggan'));
    }

    public function create()
    {
        $id_toko = $this->getActiveTokoId();

        if (!$id_toko) {
            return redirect()->route('owner.toko.index')->with('warning', 'Silakan pilih Toko/Cabang terlebih dahulu.');
        }

        return view('owner.pelanggan.create');
    }

    public function store(Request $request)
    {
        $id_toko = $this->getActiveTokoId();

        if (!$id_toko) {
            return redirect()->route('owner.toko.index')->with('error', 'Toko belum dipilih');
        }

        $count = Pelanggan::where('id_toko', $id_toko)->count();
        $kode = 'PLG-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        $request->validate([
            'nama_pelanggan' => 'required',
        ]);

        Pelanggan::create([
            'id_toko'        => $id_toko,
            'kode_pelanggan' => $kode,
            'nama_pelanggan' => $request->nama_pelanggan,
            'no_hp'          => $request->no_hp,
            'alamat'         => $request->alamat,
            'wilayah'        => $request->wilayah,
            'limit_piutang'  => $request->limit_piutang ?? 0,
            'kategori_harga' => $request->kategori_harga ?? 'umum',
        ]);

        return redirect()->route('owner.pelanggan.index')->with('success', 'Pelanggan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        return view('owner.pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        $request->validate([
            'nama_pelanggan' => 'required',
        ]);

        $pelanggan->update([
            'nama_pelanggan' => $request->nama_pelanggan,
            'no_hp'          => $request->no_hp,
            'alamat'         => $request->alamat,
            'wilayah'        => $request->wilayah,
            'limit_piutang'  => $request->limit_piutang ?? 0,
            'kategori_harga' => $request->kategori_harga ?? 'umum',
        ]);

        return redirect()->route('owner.pelanggan.index')->with('success', 'Pelanggan berhasil diupdate');
    }

    public function destroy($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->delete();

        return redirect()->route('owner.pelanggan.index')->with('success', 'Pelanggan berhasil dihapus');
    }

    public function show($id)
    {
        $pelanggan = Pelanggan::with('toko')->findOrFail($id);
        
        

        if ($pelanggan->toko->id_perusahaan != Auth::user()->id_perusahaan) {
            return redirect()->route('owner.pelanggan.index')
                           ->with('error', 'Anda tidak memiliki akses ke pelanggan ini');
        }

        

        $piutang = $pelanggan->piutang()
            ->orderBy('tanggal', 'desc')
            ->orderBy('id_piutang', 'desc')
            ->get();

        

        $saldoPiutang = $pelanggan->saldo_piutang;

        return view('owner.pelanggan.show', compact('pelanggan', 'piutang', 'saldoPiutang'));
    }

    public function piutangIndex(Request $request)
    {
        

        $userStores = Toko::where('id_perusahaan', Auth::user()->id_perusahaan)
            ->orderBy('nama_toko')
            ->get();

        

        // OPTIMIZATION: Do not load all customers. Pass empty collection.
        // But if filtering, we need to pass the selected customer name.
        $selectedPelanggan = null;
        if ($request->filled('id_pelanggan')) {
            $selectedPelanggan = Pelanggan::find($request->id_pelanggan);
        }
        
        $pelanggans = collect([]);
        if ($selectedPelanggan) {
            $pelanggans->push($selectedPelanggan);
        }

        

        $query = UtangPiutangPelanggan::with(['pelanggan.toko'])
            ->whereHas('pelanggan.toko', function($q) {
                $q->where('id_perusahaan', Auth::user()->id_perusahaan);
            });

        

        if ($request->filled('id_pelanggan')) {
            $query->where('id_pelanggan', $request->id_pelanggan);
        }

        

        if ($request->filled('jenis_transaksi')) {
            $query->where('jenis_transaksi', $request->jenis_transaksi);
        }

        

        if ($request->filled('tanggal_dari')) {
            $query->where('tanggal', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->where('tanggal', '<=', $request->tanggal_sampai);
        }

        $transaksi = $query->orderBy('tanggal', 'desc')
            ->orderBy('id_piutang', 'desc')
            ->paginate(20);

        // Calculate Summary Statistics
        $summaryQuery = UtangPiutangPelanggan::whereHas('pelanggan.toko', function($q) {
            $q->where('id_perusahaan', Auth::user()->id_perusahaan);
        });

        if ($request->filled('id_pelanggan')) {
            $summaryQuery->where('id_pelanggan', $request->id_pelanggan);
        }
        if ($request->filled('jenis_transaksi')) {
            $summaryQuery->where('jenis_transaksi', $request->jenis_transaksi);
        }
        if ($request->filled('tanggal_dari')) {
            $summaryQuery->where('tanggal', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $summaryQuery->where('tanggal', '<=', $request->tanggal_sampai);
        }

        $summary = [
            'total_piutang' => (clone $summaryQuery)->where('jenis_transaksi', 'piutang')->sum('nominal'),
            'total_bayar'   => (clone $summaryQuery)->where('jenis_transaksi', 'pembayaran')->sum('nominal'),
        ];
        $summary['saldo'] = $summary['total_piutang'] - $summary['total_bayar'];

        return view('owner.pelanggan.piutang.index', compact('transaksi', 'pelanggans', 'userStores', 'summary'));
    }

    public function piutangCreate(Request $request)
    {
        

        $pelanggans = Pelanggan::with('toko')
            ->whereHas('toko', function($q) {
                $q->where('id_perusahaan', Auth::user()->id_perusahaan);
            })
            ->orderBy('nama_pelanggan')
            ->get();

        

        $selectedPelangganId = $request->query('id_pelanggan');

        return view('owner.pelanggan.piutang.create', compact('pelanggans', 'selectedPelangganId'));
    }

    public function piutangStore(Request $request)
    {
        $request->validate([
            'id_pelanggan'     => 'required|exists:pelanggan,id_pelanggan',
            'tanggal'          => 'required|date',
            'jenis_transaksi'  => 'required|in:piutang,pembayaran',
            'nominal'          => 'required|numeric|min:0',
            'keterangan'       => 'nullable',
            'no_referensi'     => 'nullable|max:50',
        ]);

        DB::beginTransaction();
        try {
            

            $lastTransaction = UtangPiutangPelanggan::where('id_pelanggan', $request->id_pelanggan)
                ->orderBy('tanggal', 'desc')
                ->orderBy('id_piutang', 'desc')
                ->first();

            $saldoSebelumnya = $lastTransaction ? $lastTransaction->saldo_piutang : 0;

            

            if ($request->jenis_transaksi == 'piutang') {
                $saldoBaru = $saldoSebelumnya + $request->nominal;
            } else { 

                $saldoBaru = $saldoSebelumnya - $request->nominal;
            }

            

            UtangPiutangPelanggan::create([
                'id_pelanggan'    => $request->id_pelanggan,
                'tanggal'         => $request->tanggal,
                'jenis_transaksi' => $request->jenis_transaksi,
                'nominal'         => $request->nominal,
                'keterangan'      => $request->keterangan,
                'no_referensi'    => $request->no_referensi,
                'saldo_piutang'   => $saldoBaru,
            ]);

            DB::commit();

            return redirect()->route('owner.pelanggan.piutang.index')
                           ->with('success', 'Transaksi berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function piutangEdit($id)
    {
        $transaksi = UtangPiutangPelanggan::with('pelanggan.toko')->findOrFail($id);
        
        

        if ($transaksi->pelanggan->toko->id_perusahaan != Auth::user()->id_perusahaan) {
            return redirect()->route('owner.pelanggan.piutang.index')
                           ->with('error', 'Anda tidak memiliki akses ke transaksi ini');
        }

        $pelanggans = Pelanggan::with('toko')
            ->whereHas('toko', function($q) {
                $q->where('id_perusahaan', Auth::user()->id_perusahaan);
            })
            ->orderBy('nama_pelanggan')
            ->get();

        return view('owner.pelanggan.piutang.edit', compact('transaksi', 'pelanggans'));
    }

    public function piutangUpdate(Request $request, $id)
    {
        $transaksi = UtangPiutangPelanggan::with('pelanggan.toko')->findOrFail($id);
        
        

        if ($transaksi->pelanggan->toko->id_perusahaan != Auth::user()->id_perusahaan) {
            return redirect()->route('owner.pelanggan.piutang.index')
                           ->with('error', 'Anda tidak memiliki akses ke transaksi ini');
        }

        $request->validate([
            'id_pelanggan'     => 'required|exists:pelanggan,id_pelanggan',
            'tanggal'          => 'required|date',
            'jenis_transaksi'  => 'required|in:piutang,pembayaran',
            'nominal'          => 'required|numeric|min:0',
            'keterangan'       => 'nullable',
            'no_referensi'     => 'nullable|max:50',
        ]);

        DB::beginTransaction();
        try {
            

            $transaksi->update([
                'id_pelanggan'    => $request->id_pelanggan,
                'tanggal'         => $request->tanggal,
                'jenis_transaksi' => $request->jenis_transaksi,
                'nominal'         => $request->nominal,
                'keterangan'      => $request->keterangan,
                'no_referensi'    => $request->no_referensi,
            ]);

            

            $this->recalculateSaldo($request->id_pelanggan);

            DB::commit();

            return redirect()->route('owner.pelanggan.piutang.index')
                           ->with('success', 'Transaksi berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function piutangDestroy($id)
    {
        $transaksi = UtangPiutangPelanggan::with('pelanggan.toko')->findOrFail($id);
        
        

        if ($transaksi->pelanggan->toko->id_perusahaan != Auth::user()->id_perusahaan) {
            return redirect()->route('owner.pelanggan.piutang.index')
                           ->with('error', 'Anda tidak memiliki akses ke transaksi ini');
        }

        DB::beginTransaction();
        try {
            $id_pelanggan = $transaksi->id_pelanggan;
            $transaksi->delete();

            

            $this->recalculateSaldo($id_pelanggan);

            DB::commit();

            return redirect()->route('owner.pelanggan.piutang.index')
                           ->with('success', 'Transaksi berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    

    private function recalculateSaldo($id_pelanggan)
    {
        $transaksis = UtangPiutangPelanggan::where('id_pelanggan', $id_pelanggan)
            ->orderBy('tanggal', 'asc')
            ->orderBy('id_piutang', 'asc')
            ->get();

        $saldo = 0;
        foreach ($transaksis as $t) {
            if ($t->jenis_transaksi == 'piutang') {
                $saldo += $t->nominal;
            } else { 

                $saldo -= $t->nominal;
            }
            $t->saldo_piutang = $saldo;
            $t->save();
        }
    }


    public function searchPelanggan(Request $request)
    {
        $id_toko = $this->getActiveTokoId();
        $keyword = $request->get('q');

        $query = Pelanggan::select('id_pelanggan', 'nama_pelanggan', 'kode_pelanggan', 'no_hp')
            ->where('id_toko', $id_toko);

        if ($keyword) {
            $query->where(function($q) use ($keyword) {
                $q->where('nama_pelanggan', 'like', "%{$keyword}%")
                  ->orWhere('kode_pelanggan', 'like', "%{$keyword}%")
                  ->orWhere('no_hp', 'like', "%{$keyword}%");
            });
        }

        $pelanggan = $query->limit(20)->get();

        return response()->json($pelanggan);
    }
}
