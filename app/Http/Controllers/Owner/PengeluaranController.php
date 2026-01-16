<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Pengeluaran;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengeluaranController extends Controller
{
    public function index(Request $request)
    {
        $idToko = session('toko_active_id');
        
        if (!$idToko) {
            return redirect()->route('owner.dashboard')
                           ->with('error', 'Silakan pilih toko terlebih dahulu');
        }

        $query = Pengeluaran::with(['user'])
            ->where('id_toko', $idToko);

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_pengeluaran', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_pengeluaran', '<=', $request->tanggal_sampai);
        }
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $pengeluarans = $query->orderBy('tanggal_pengeluaran', 'desc')->paginate(20);


        $today = now()->format('Y-m-d');
        $thisMonth = now()->month;
        $thisYear = now()->year;

        $summary = [
            'total_pengeluaran' => $query->sum('jumlah'), // Respects filters
            'jumlah_transaksi' => $query->count(),        // Respects filters
            'hari_ini' => Pengeluaran::where('id_toko', $idToko)->whereDate('tanggal_pengeluaran', $today)->sum('jumlah'),
            'bulan_ini' => Pengeluaran::where('id_toko', $idToko)->whereMonth('tanggal_pengeluaran', $thisMonth)->whereYear('tanggal_pengeluaran', $thisYear)->sum('jumlah'),
            'tahun_ini' => Pengeluaran::where('id_toko', $idToko)->whereYear('tanggal_pengeluaran', $thisYear)->sum('jumlah'),
        ];

        return view('owner.pengeluaran.index', compact('pengeluarans', 'summary'));
    }

    public function create()
    {
        $idToko = session('toko_active_id');
        
        if (!$idToko) {
            return redirect()->route('owner.dashboard')
                           ->with('error', 'Silakan pilih toko terlebih dahulu');
        }

        $today = now()->format('Ymd');
        $lastPengeluaran = Pengeluaran::where('id_toko', $idToko)
            ->where('kode_pengeluaran', 'like', "BYA-{$today}-%")
            ->orderBy('kode_pengeluaran', 'desc')
            ->first();

        if ($lastPengeluaran) {
            $lastNumber = intval(substr($lastPengeluaran->kode_pengeluaran, -3));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $kodePengeluaran = "BYA-{$today}-" . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        return view('owner.pengeluaran.create', compact('kodePengeluaran'));
    }

    public function store(Request $request)
    {
        $idToko = session('toko_active_id');
        
        if (!$idToko) {
            return redirect()->route('owner.dashboard')
                           ->with('error', 'Silakan pilih toko terlebih dahulu');
        }

        $request->validate([
            'kode_pengeluaran' => 'required|unique:pengeluaran,kode_pengeluaran|max:20',
            'tanggal_pengeluaran' => 'required|date',
            'kategori' => 'required|in:Gaji,Listrik,Air,Sewa,ATK,Transportasi,Pemeliharaan,Pajak,Lainnya',
            'deskripsi' => 'required',
            'jumlah' => 'required|numeric|min:0',
            'metode_bayar' => 'required|in:Tunai,Transfer,Kredit',
            'bukti_pembayaran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'keterangan' => 'nullable',
        ]);

        $data = [
            'id_toko' => $idToko,
            'id_user' => Auth::id(),
            'kode_pengeluaran' => $request->kode_pengeluaran,
            'tanggal_pengeluaran' => $request->tanggal_pengeluaran,
            'kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi,
            'jumlah' => $request->jumlah,
            'metode_bayar' => $request->metode_bayar,
            'keterangan' => $request->keterangan,
        ];

        if ($request->hasFile('bukti_pembayaran')) {
            $buktiPath = $request->file('bukti_pembayaran')->store('pengeluaran', 'public');
            $data['bukti_pembayaran'] = $buktiPath;
        }

        Pengeluaran::create($data);

        return redirect()->route('owner.pengeluaran.index')
                       ->with('success', 'Pengeluaran berhasil ditambahkan');
    }

    public function show($id)
    {
        $pengeluaran = Pengeluaran::with(['toko', 'user'])->findOrFail($id);
        
        if ($pengeluaran->toko->id_perusahaan != Auth::user()->id_perusahaan) {
            return redirect()->route('owner.pengeluaran.index')
                           ->with('error', 'Anda tidak memiliki akses ke pengeluaran ini');
        }

        return view('owner.pengeluaran.show', compact('pengeluaran'));
    }

    public function edit($id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);
        
        if ($pengeluaran->toko->id_perusahaan != Auth::user()->id_perusahaan) {
            return redirect()->route('owner.pengeluaran.index')
                           ->with('error', 'Anda tidak memiliki akses ke pengeluaran ini');
        }

        return view('owner.pengeluaran.edit', compact('pengeluaran'));
    }

    public function update(Request $request, $id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);
        
        if ($pengeluaran->toko->id_perusahaan != Auth::user()->id_perusahaan) {
            return redirect()->route('owner.pengeluaran.index')
                           ->with('error', 'Anda tidak memiliki akses ke pengeluaran ini');
        }

        $request->validate([
            'kode_pengeluaran' => 'required|max:20|unique:pengeluaran,kode_pengeluaran,' . $id . ',id_pengeluaran',
            'tanggal_pengeluaran' => 'required|date',
            'kategori' => 'required|in:Gaji,Listrik,Air,Sewa,ATK,Transportasi,Pemeliharaan,Pajak,Lainnya',
            'deskripsi' => 'required',
            'jumlah' => 'required|numeric|min:0',
            'metode_bayar' => 'required|in:Tunai,Transfer,Kredit',
            'bukti_pembayaran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'keterangan' => 'nullable',
        ]);

        $data = [
            'kode_pengeluaran' => $request->kode_pengeluaran,
            'tanggal_pengeluaran' => $request->tanggal_pengeluaran,
            'kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi,
            'jumlah' => $request->jumlah,
            'metode_bayar' => $request->metode_bayar,
            'keterangan' => $request->keterangan,
        ];

        if ($request->hasFile('bukti_pembayaran')) {
            if ($pengeluaran->bukti_pembayaran && Storage::disk('public')->exists($pengeluaran->bukti_pembayaran)) {
                Storage::disk('public')->delete($pengeluaran->bukti_pembayaran);
            }
            
            $buktiPath = $request->file('bukti_pembayaran')->store('pengeluaran', 'public');
            $data['bukti_pembayaran'] = $buktiPath;
        }

        $pengeluaran->update($data);

        return redirect()->route('owner.pengeluaran.index')
                       ->with('success', 'Pengeluaran berhasil diupdate');
    }

    public function destroy($id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);
        
        if ($pengeluaran->toko->id_perusahaan != Auth::user()->id_perusahaan) {
            return redirect()->route('owner.pengeluaran.index')
                           ->with('error', 'Anda tidak memiliki akses ke pengeluaran ini');
        }

        if ($pengeluaran->bukti_pembayaran && Storage::disk('public')->exists($pengeluaran->bukti_pembayaran)) {
            Storage::disk('public')->delete($pengeluaran->bukti_pembayaran);
        }

        $pengeluaran->delete();
        
        return redirect()->route('owner.pengeluaran.index')
                       ->with('success', 'Pengeluaran berhasil dihapus');
    }
}
