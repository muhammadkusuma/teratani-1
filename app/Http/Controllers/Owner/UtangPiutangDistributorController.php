<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\UtangPiutangDistributor;
use App\Models\Distributor;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UtangPiutangDistributorController extends Controller
{
    public function index(Request $request)
    {
        

        $userStores = Toko::where('id_perusahaan', Auth::user()->id_perusahaan)
            ->orderBy('nama_toko')
            ->get();

        

        $distributors = Distributor::with('toko')
            ->whereHas('toko', function($q) {
                $q->where('id_perusahaan', Auth::user()->id_perusahaan);
            })
            ->active()
            ->orderBy('nama_distributor')
            ->get();

        

        $query = UtangPiutangDistributor::with(['distributor.toko'])
            ->whereHas('distributor.toko', function($q) {
                $q->where('id_perusahaan', Auth::user()->id_perusahaan);
            });

        

        if ($request->filled('id_distributor')) {
            $query->where('id_distributor', $request->id_distributor);
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
            ->orderBy('id_utang_piutang', 'desc')
            ->paginate(20);

        return view('owner.utang_piutang_distributor.index', compact('transaksi', 'distributors', 'userStores'));
    }

    public function create(Request $request)
    {
        

        $distributors = Distributor::with('toko')
            ->whereHas('toko', function($q) {
                $q->where('id_perusahaan', Auth::user()->id_perusahaan);
            })
            ->active()
            ->orderBy('nama_distributor')
            ->get();

        

        $selectedDistributorId = $request->query('id_distributor');

        return view('owner.utang_piutang_distributor.create', compact('distributors', 'selectedDistributorId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_distributor'   => 'required|exists:distributor,id_distributor',
            'tanggal'          => 'required|date',
            'jenis_transaksi'  => 'required|in:utang,pembayaran',
            'nominal'          => 'required|numeric|min:0',
            'keterangan'       => 'nullable',
            'no_referensi'     => 'nullable|max:50',
        ]);

        DB::beginTransaction();
        try {
            

            $lastTransaction = UtangPiutangDistributor::where('id_distributor', $request->id_distributor)
                ->orderBy('tanggal', 'desc')
                ->orderBy('id_utang_piutang', 'desc')
                ->first();

            $saldoSebelumnya = $lastTransaction ? $lastTransaction->saldo_utang : 0;

            

            if ($request->jenis_transaksi == 'utang') {
                $saldoBaru = $saldoSebelumnya + $request->nominal;
            } else { 

                $saldoBaru = $saldoSebelumnya - $request->nominal;
            }

            

            UtangPiutangDistributor::create([
                'id_distributor'  => $request->id_distributor,
                'tanggal'         => $request->tanggal,
                'jenis_transaksi' => $request->jenis_transaksi,
                'nominal'         => $request->nominal,
                'keterangan'      => $request->keterangan,
                'no_referensi'    => $request->no_referensi,
                'saldo_utang'     => $saldoBaru,
            ]);

            DB::commit();

            return redirect()->route('owner.utang-piutang-distributor.index')
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
        $transaksi = UtangPiutangDistributor::with('distributor.toko')->findOrFail($id);
        
        

        if ($transaksi->distributor->toko->id_perusahaan != Auth::user()->id_perusahaan) {
            return redirect()->route('owner.utang-piutang-distributor.index')
                           ->with('error', 'Anda tidak memiliki akses ke transaksi ini');
        }

        $distributors = Distributor::with('toko')
            ->whereHas('toko', function($q) {
                $q->where('id_perusahaan', Auth::user()->id_perusahaan);
            })
            ->active()
            ->orderBy('nama_distributor')
            ->get();

        return view('owner.utang_piutang_distributor.edit', compact('transaksi', 'distributors'));
    }

    public function update(Request $request, $id)
    {
        $transaksi = UtangPiutangDistributor::with('distributor.toko')->findOrFail($id);
        
        

        if ($transaksi->distributor->toko->id_perusahaan != Auth::user()->id_perusahaan) {
            return redirect()->route('owner.utang-piutang-distributor.index')
                           ->with('error', 'Anda tidak memiliki akses ke transaksi ini');
        }

        $request->validate([
            'id_distributor'   => 'required|exists:distributor,id_distributor',
            'tanggal'          => 'required|date',
            'jenis_transaksi'  => 'required|in:utang,pembayaran',
            'nominal'          => 'required|numeric|min:0',
            'keterangan'       => 'nullable',
            'no_referensi'     => 'nullable|max:50',
        ]);

        DB::beginTransaction();
        try {
            

            $transaksi->update([
                'id_distributor'  => $request->id_distributor,
                'tanggal'         => $request->tanggal,
                'jenis_transaksi' => $request->jenis_transaksi,
                'nominal'         => $request->nominal,
                'keterangan'      => $request->keterangan,
                'no_referensi'    => $request->no_referensi,
            ]);

            

            $this->recalculateSaldo($request->id_distributor);

            DB::commit();

            return redirect()->route('owner.utang-piutang-distributor.index')
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
        $transaksi = UtangPiutangDistributor::with('distributor.toko')->findOrFail($id);
        
        

        if ($transaksi->distributor->toko->id_perusahaan != Auth::user()->id_perusahaan) {
            return redirect()->route('owner.utang-piutang-distributor.index')
                           ->with('error', 'Anda tidak memiliki akses ke transaksi ini');
        }

        DB::beginTransaction();
        try {
            $id_distributor = $transaksi->id_distributor;
            $transaksi->delete();

            

            $this->recalculateSaldo($id_distributor);

            DB::commit();

            return redirect()->route('owner.utang-piutang-distributor.index')
                           ->with('success', 'Transaksi berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    

    private function recalculateSaldo($id_distributor)
    {
        $transaksis = UtangPiutangDistributor::where('id_distributor', $id_distributor)
            ->orderBy('tanggal', 'asc')
            ->orderBy('id_utang_piutang', 'asc')
            ->get();

        $saldo = 0;
        foreach ($transaksis as $t) {
            if ($t->jenis_transaksi == 'utang') {
                $saldo += $t->nominal;
            } else { 

                $saldo -= $t->nominal;
            }
            $t->saldo_utang = $saldo;
            $t->save();
        }
    }
}
