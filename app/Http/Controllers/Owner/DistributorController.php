<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Distributor;
use App\Models\Toko;
use App\Models\UtangPiutangDistributor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DistributorController extends Controller
{
    public function index(Request $request)
    {
        // Get user's company stores
        $userStores = Toko::where('id_perusahaan', Auth::user()->id_perusahaan)
            ->orderBy('nama_toko')
            ->get();

        // Build query for distributors from user's company stores
        $query = Distributor::with('toko')
            ->whereHas('toko', function($q) {
                $q->where('id_perusahaan', Auth::user()->id_perusahaan);
            });

        // Filter by store if selected
        if ($request->filled('id_toko')) {
            $query->where('id_toko', $request->id_toko);
        }

        $distributors = $query->orderBy('nama_distributor')->paginate(20);

        return view('owner.distributor.index', compact('distributors', 'userStores'));
    }

    public function show($id)
    {
        $distributor = Distributor::with('toko')->findOrFail($id);
        
        // Check if distributor belongs to user's company
        if ($distributor->toko->id_perusahaan != Auth::user()->id_perusahaan) {
            return redirect()->route('owner.distributor.index')
                           ->with('error', 'Anda tidak memiliki akses ke distributor ini');
        }

        // Get all utang/piutang transactions for this distributor
        $utangPiutang = $distributor->utangPiutang()
            ->orderBy('tanggal', 'desc')
            ->orderBy('id_utang_piutang', 'desc')
            ->get();

        // Get current saldo
        $saldoUtang = $distributor->saldo_utang;

        return view('owner.distributor.show', compact('distributor', 'utangPiutang', 'saldoUtang'));
    }

    public function create()
    {
        // Get user's company stores
        $userStores = Toko::where('id_perusahaan', Auth::user()->id_perusahaan)
            ->where('is_active', true)
            ->orderBy('nama_toko')
            ->get();

        // Generate next distributor code
        $lastDistributor = Distributor::orderBy('id_distributor', 'desc')->first();
        $nextNumber = $lastDistributor ? (intval(substr($lastDistributor->kode_distributor, 4)) + 1) : 1;
        $kodeDistributor = 'DIST' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        return view('owner.distributor.create', compact('userStores', 'kodeDistributor'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_toko'           => 'required|exists:toko,id_toko',
            'kode_distributor'  => 'required|unique:distributor,kode_distributor|max:20',
            'nama_distributor'  => 'required|max:100',
            'nama_perusahaan'   => 'nullable|max:150',
            'alamat'            => 'nullable',
            'kota'              => 'nullable|max:50',
            'provinsi'          => 'nullable|max:50',
            'kode_pos'          => 'nullable|max:10',
            'no_telp'           => 'nullable|max:20',
            'email'             => 'nullable|email|max:100',
            'nama_kontak'       => 'nullable|max:100',
            'no_hp_kontak'      => 'nullable|max:20',
            'npwp'              => 'nullable|max:30',
            'keterangan'        => 'nullable',
            'hutang_awal'       => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $distributor = Distributor::create([
                'id_toko'           => $request->id_toko,
                'kode_distributor'  => $request->kode_distributor,
                'nama_distributor'  => $request->nama_distributor,
                'nama_perusahaan'   => $request->nama_perusahaan,
                'alamat'            => $request->alamat,
                'kota'              => $request->kota,
                'provinsi'          => $request->provinsi,
                'kode_pos'          => $request->kode_pos,
                'no_telp'           => $request->no_telp,
                'email'             => $request->email,
                'nama_kontak'       => $request->nama_kontak,
                'no_hp_kontak'      => $request->no_hp_kontak,
                'npwp'              => $request->npwp,
                'keterangan'        => $request->keterangan,
                'is_active'         => $request->has('is_active'),
            ]);

            // Create initial debt transaction if hutang_awal is provided
            if ($request->filled('hutang_awal') && $request->hutang_awal > 0) {
                \App\Models\UtangPiutangDistributor::create([
                    'id_distributor'  => $distributor->id_distributor,
                    'tanggal'         => now()->toDateString(),
                    'jenis_transaksi' => 'utang',
                    'nominal'         => $request->hutang_awal,
                    'keterangan'      => 'Hutang awal saat pendaftaran distributor',
                    'no_referensi'    => null,
                    'saldo_utang'     => $request->hutang_awal,
                ]);
            }

            DB::commit();

            return redirect()->route('owner.distributor.index')
                           ->with('success', 'Distributor berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $distributor = Distributor::findOrFail($id);
        
        // Check if distributor belongs to user's company
        if ($distributor->toko->id_perusahaan != Auth::user()->id_perusahaan) {
            return redirect()->route('owner.distributor.index')
                           ->with('error', 'Anda tidak memiliki akses ke distributor ini');
        }

        $userStores = Toko::where('id_perusahaan', Auth::user()->id_perusahaan)
            ->where('is_active', true)
            ->orderBy('nama_toko')
            ->get();

        return view('owner.distributor.edit', compact('distributor', 'userStores'));
    }

    public function update(Request $request, $id)
    {
        $distributor = Distributor::findOrFail($id);
        
        // Check if distributor belongs to user's company
        if ($distributor->toko->id_perusahaan != Auth::user()->id_perusahaan) {
            return redirect()->route('owner.distributor.index')
                           ->with('error', 'Anda tidak memiliki akses ke distributor ini');
        }

        $request->validate([
            'id_toko'           => 'required|exists:toko,id_toko',
            'kode_distributor'  => 'required|max:20|unique:distributor,kode_distributor,' . $id . ',id_distributor',
            'nama_distributor'  => 'required|max:100',
            'nama_perusahaan'   => 'nullable|max:150',
            'alamat'            => 'nullable',
            'kota'              => 'nullable|max:50',
            'provinsi'          => 'nullable|max:50',
            'kode_pos'          => 'nullable|max:10',
            'no_telp'           => 'nullable|max:20',
            'email'             => 'nullable|email|max:100',
            'nama_kontak'       => 'nullable|max:100',
            'no_hp_kontak'      => 'nullable|max:20',
            'npwp'              => 'nullable|max:30',
            'keterangan'        => 'nullable',
            'hutang_awal'       => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $distributor->update([
                'id_toko'           => $request->id_toko,
                'kode_distributor'  => $request->kode_distributor,
                'nama_distributor'  => $request->nama_distributor,
                'nama_perusahaan'   => $request->nama_perusahaan,
                'alamat'            => $request->alamat,
                'kota'              => $request->kota,
                'provinsi'          => $request->provinsi,
                'kode_pos'          => $request->kode_pos,
                'no_telp'           => $request->no_telp,
                'email'             => $request->email,
                'nama_kontak'       => $request->nama_kontak,
                'no_hp_kontak'      => $request->no_hp_kontak,
                'npwp'              => $request->npwp,
                'keterangan'        => $request->keterangan,
                'is_active'         => $request->has('is_active'),
            ]);

            // Create additional debt transaction if hutang_awal is provided
            if ($request->filled('hutang_awal') && $request->hutang_awal > 0) {
                // Get current saldo
                $lastTransaction = \App\Models\UtangPiutangDistributor::where('id_distributor', $id)
                    ->orderBy('tanggal', 'desc')
                    ->orderBy('id_utang_piutang', 'desc')
                    ->first();

                $saldoSebelumnya = $lastTransaction ? $lastTransaction->saldo_utang : 0;
                $saldoBaru = $saldoSebelumnya + $request->hutang_awal;

                \App\Models\UtangPiutangDistributor::create([
                    'id_distributor'  => $id,
                    'tanggal'         => now()->toDateString(),
                    'jenis_transaksi' => 'utang',
                    'nominal'         => $request->hutang_awal,
                    'keterangan'      => 'Penambahan hutang melalui edit distributor',
                    'no_referensi'    => null,
                    'saldo_utang'     => $saldoBaru,
                ]);
            }

            DB::commit();

            return redirect()->route('owner.distributor.index')
                           ->with('success', 'Distributor berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $distributor = Distributor::findOrFail($id);
        
        // Check if distributor belongs to user's company
        if ($distributor->toko->id_perusahaan != Auth::user()->id_perusahaan) {
            return redirect()->route('owner.distributor.index')
                           ->with('error', 'Anda tidak memiliki akses ke distributor ini');
        }

        $distributor->delete();
        
        return redirect()->route('owner.distributor.index')
                       ->with('success', 'Distributor berhasil dihapus');
    }

    public function hutangIndex(Request $request)
    {
        // Get user's company stores
        $userStores = Toko::where('id_perusahaan', Auth::user()->id_perusahaan)
            ->orderBy('nama_toko')
            ->get();

        // Get distributors from user's company stores
        $distributors = Distributor::with('toko')
            ->whereHas('toko', function($q) {
                $q->where('id_perusahaan', Auth::user()->id_perusahaan);
            })
            ->active()
            ->orderBy('nama_distributor')
            ->get();

        // Build query
        $query = UtangPiutangDistributor::with(['distributor.toko'])
            ->whereHas('distributor.toko', function($q) {
                $q->where('id_perusahaan', Auth::user()->id_perusahaan);
            });

        // Filter by distributor
        if ($request->filled('id_distributor')) {
            $query->where('id_distributor', $request->id_distributor);
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
            ->orderBy('id_utang_piutang', 'desc')
            ->paginate(20);

        return view('owner.distributor.hutang.index', compact('transaksi', 'distributors', 'userStores'));
    }

    public function hutangCreate(Request $request)
    {
        // Get distributors from user's company stores
        $distributors = Distributor::with('toko')
            ->whereHas('toko', function($q) {
                $q->where('id_perusahaan', Auth::user()->id_perusahaan);
            })
            ->active()
            ->orderBy('nama_distributor')
            ->get();

        // Get pre-selected distributor ID from query parameter
        $selectedDistributorId = $request->query('id_distributor');

        return view('owner.distributor.hutang.create', compact('distributors', 'selectedDistributorId'));
    }

    public function hutangStore(Request $request)
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
            // Get saldo terakhir
            $lastTransaction = UtangPiutangDistributor::where('id_distributor', $request->id_distributor)
                ->orderBy('tanggal', 'desc')
                ->orderBy('id_utang_piutang', 'desc')
                ->first();

            $saldoSebelumnya = $lastTransaction ? $lastTransaction->saldo_utang : 0;

            // Hitung saldo baru
            if ($request->jenis_transaksi == 'utang') {
                $saldoBaru = $saldoSebelumnya + $request->nominal;
            } else { // pembayaran
                $saldoBaru = $saldoSebelumnya - $request->nominal;
            }

            // Create transaction
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

            return redirect()->route('owner.distributor.hutang.index')
                           ->with('success', 'Transaksi berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function hutangEdit($id)
    {
        $transaksi = UtangPiutangDistributor::with('distributor.toko')->findOrFail($id);
        
        // Check access
        if ($transaksi->distributor->toko->id_perusahaan != Auth::user()->id_perusahaan) {
            return redirect()->route('owner.distributor.hutang.index')
                           ->with('error', 'Anda tidak memiliki akses ke transaksi ini');
        }

        $distributors = Distributor::with('toko')
            ->whereHas('toko', function($q) {
                $q->where('id_perusahaan', Auth::user()->id_perusahaan);
            })
            ->active()
            ->orderBy('nama_distributor')
            ->get();

        return view('owner.distributor.hutang.edit', compact('transaksi', 'distributors'));
    }

    public function hutangUpdate(Request $request, $id)
    {
        $transaksi = UtangPiutangDistributor::with('distributor.toko')->findOrFail($id);
        
        // Check access
        if ($transaksi->distributor->toko->id_perusahaan != Auth::user()->id_perusahaan) {
            return redirect()->route('owner.distributor.hutang.index')
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
            // Update transaksi
            $transaksi->update([
                'id_distributor'  => $request->id_distributor,
                'tanggal'         => $request->tanggal,
                'jenis_transaksi' => $request->jenis_transaksi,
                'nominal'         => $request->nominal,
                'keterangan'      => $request->keterangan,
                'no_referensi'    => $request->no_referensi,
            ]);

            // Recalculate all balances for this distributor
            $this->recalculateSaldo($request->id_distributor);

            DB::commit();

            return redirect()->route('owner.distributor.hutang.index')
                           ->with('success', 'Transaksi berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function hutangDestroy($id)
    {
        $transaksi = UtangPiutangDistributor::with('distributor.toko')->findOrFail($id);
        
        // Check access
        if ($transaksi->distributor->toko->id_perusahaan != Auth::user()->id_perusahaan) {
            return redirect()->route('owner.distributor.hutang.index')
                           ->with('error', 'Anda tidak memiliki akses ke transaksi ini');
        }

        DB::beginTransaction();
        try {
            $id_distributor = $transaksi->id_distributor;
            $transaksi->delete();

            // Recalculate all balances for this distributor
            $this->recalculateSaldo($id_distributor);

            DB::commit();

            return redirect()->route('owner.distributor.hutang.index')
                           ->with('success', 'Transaksi berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Helper method untuk recalculate saldo
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
            } else { // pembayaran
                $saldo -= $t->nominal;
            }
            $t->saldo_utang = $saldo;
            $t->save();
        }
    }
}
