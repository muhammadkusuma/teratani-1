<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\UtangPiutangPelanggan;
use App\Models\Pelanggan;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UtangPiutangPelangganController extends Controller
{
    public function index(Request $request)
    {
        // Get user's company stores
        $userStores = Toko::where('id_perusahaan', Auth::user()->id_perusahaan)
            ->orderBy('nama_toko')
            ->get();

        // Get pelanggan from user's company stores
        $pelanggans = Pelanggan::with('toko')
            ->whereHas('toko', function($q) {
                $q->where('id_perusahaan', Auth::user()->id_perusahaan);
            })
            ->orderBy('nama_pelanggan')
            ->get();

        // Build query
        $query = UtangPiutangPelanggan::with(['pelanggan.toko'])
            ->whereHas('pelanggan.toko', function($q) {
                $q->where('id_perusahaan', Auth::user()->id_perusahaan);
            });

        // Filter by pelanggan
        if ($request->filled('id_pelanggan')) {
            $query->where('id_pelanggan', $request->id_pelanggan);
        }

        // Filter by jenis transaksi
        if ($request->filled('jenis_transaksi')) {
            $query->where('jenis_transaksi', $request->jenis_transaksi);
        }

        // Filter by date range
        if ($request->filled('tanggal_dari')) {
            $query->where('tanggal', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->where('tanggal', '<=', $request->tanggal_sampai);
        }

        $transaksi = $query->orderBy('tanggal', 'desc')
            ->orderBy('id_piutang', 'desc')
            ->paginate(20);

        return view('owner.utang_piutang_pelanggan.index', compact('transaksi', 'pelanggans', 'userStores'));
    }

    public function create()
    {
        // Get pelanggan from user's company stores
        $pelanggans = Pelanggan::with('toko')
            ->whereHas('toko', function($q) {
                $q->where('id_perusahaan', Auth::user()->id_perusahaan);
            })
            ->orderBy('nama_pelanggan')
            ->get();

        return view('owner.utang_piutang_pelanggan.create', compact('pelanggans'));
    }

    public function store(Request $request)
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
            // Get saldo terakhir
            $lastTransaction = UtangPiutangPelanggan::where('id_pelanggan', $request->id_pelanggan)
                ->orderBy('tanggal', 'desc')
                ->orderBy('id_piutang', 'desc')
                ->first();

            $saldoSebelumnya = $lastTransaction ? $lastTransaction->saldo_piutang : 0;

            // Hitung saldo baru
            if ($request->jenis_transaksi == 'piutang') {
                $saldoBaru = $saldoSebelumnya + $request->nominal;
            } else { // pembayaran
                $saldoBaru = $saldoSebelumnya - $request->nominal;
            }

            // Create transaction
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

            return redirect()->route('owner.utang-piutang-pelanggan.index')
                           ->with('success', 'Transaksi berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $transaksi = UtangPiutangPelanggan::with('pelanggan.toko')->findOrFail($id);
        
        // Check access
        if ($transaksi->pelanggan->toko->id_perusahaan != Auth::user()->id_perusahaan) {
            return redirect()->route('owner.utang-piutang-pelanggan.index')
                           ->with('error', 'Anda tidak memiliki akses ke transaksi ini');
        }

        $pelanggans = Pelanggan::with('toko')
            ->whereHas('toko', function($q) {
                $q->where('id_perusahaan', Auth::user()->id_perusahaan);
            })
            ->orderBy('nama_pelanggan')
            ->get();

        return view('owner.utang_piutang_pelanggan.edit', compact('transaksi', 'pelanggans'));
    }

    public function update(Request $request, $id)
    {
        $transaksi = UtangPiutangPelanggan::with('pelanggan.toko')->findOrFail($id);
        
        // Check access
        if ($transaksi->pelanggan->toko->id_perusahaan != Auth::user()->id_perusahaan) {
            return redirect()->route('owner.utang-piutang-pelanggan.index')
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
            // Update transaksi
            $transaksi->update([
                'id_pelanggan'    => $request->id_pelanggan,
                'tanggal'         => $request->tanggal,
                'jenis_transaksi' => $request->jenis_transaksi,
                'nominal'         => $request->nominal,
                'keterangan'      => $request->keterangan,
                'no_referensi'    => $request->no_referensi,
            ]);

            // Recalculate all balances for this pelanggan
            $this->recalculateSaldo($request->id_pelanggan);

            DB::commit();

            return redirect()->route('owner.utang-piutang-pelanggan.index')
                           ->with('success', 'Transaksi berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $transaksi = UtangPiutangPelanggan::with('pelanggan.toko')->findOrFail($id);
        
        // Check access
        if ($transaksi->pelanggan->toko->id_perusahaan != Auth::user()->id_perusahaan) {
            return redirect()->route('owner.utang-piutang-pelanggan.index')
                           ->with('error', 'Anda tidak memiliki akses ke transaksi ini');
        }

        DB::beginTransaction();
        try {
            $id_pelanggan = $transaksi->id_pelanggan;
            $transaksi->delete();

            // Recalculate all balances for this pelanggan
            $this->recalculateSaldo($id_pelanggan);

            DB::commit();

            return redirect()->route('owner.utang-piutang-pelanggan.index')
                           ->with('success', 'Transaksi berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Helper method untuk recalculate saldo
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
            } else { // pembayaran
                $saldo -= $t->nominal;
            }
            $t->saldo_piutang = $saldo;
            $t->save();
        }
    }
}
