<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\PendapatanPasif;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PendapatanPasifController extends Controller
{
    public function index(Request $request)
    {
        $idToko = session('toko_active_id');
        
        if (!$idToko) {
            return redirect()->route('owner.dashboard')
                           ->with('error', 'Silakan pilih toko terlebih dahulu');
        }

        $query = PendapatanPasif::with(['user'])
            ->where('id_toko', $idToko);

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_pendapatan', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_pendapatan', '<=', $request->tanggal_sampai);
        }
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $pendapatan_pasifs = $query->orderBy('tanggal_pendapatan', 'desc')->paginate(20);

        $summary = [
            'total_pendapatan_pasif' => $query->sum('jumlah'),
            'jumlah_transaksi' => $query->count(),
        ];

        return view('owner.pendapatan_pasif.index', compact('pendapatan_pasifs', 'summary'));
    }

    public function create()
    {
        $idToko = session('toko_active_id');
        
        if (!$idToko) {
            return redirect()->route('owner.dashboard')
                           ->with('error', 'Silakan pilih toko terlebih dahulu');
        }

        $today = now()->format('Ymd');
        $lastPendapatanPasif = PendapatanPasif::where('id_toko', $idToko)
            ->where('kode_pendapatan', 'like', "INC-{$today}-%")
            ->orderBy('kode_pendapatan', 'desc')
            ->first();

        if ($lastPendapatanPasif) {
            $lastNumber = intval(substr($lastPendapatanPasif->kode_pendapatan, -3));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $kodePendapatanPasif = "INC-{$today}-" . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        return view('owner.pendapatan_pasif.create', compact('kodePendapatanPasif'));
    }

    public function store(Request $request)
    {
        $idToko = session('toko_active_id');
        
        if (!$idToko) {
            return redirect()->route('owner.dashboard')
                           ->with('error', 'Silakan pilih toko terlebih dahulu');
        }

        $request->validate([
            'kode_pendapatan' => 'required|unique:pendapatan_pasif,kode_pendapatan|max:20',
            'tanggal_pendapatan' => 'required|date',
            'kategori' => 'required|in:Bunga Bank,Sewa Aset,Komisi,Investasi,Lainnya',
            'sumber' => 'required',
            'jumlah' => 'required|numeric|min:0',
            'metode_terima' => 'required|in:Tunai,Transfer',
            'bukti_penerimaan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'keterangan' => 'nullable',
        ]);

        $data = [
            'id_toko' => $idToko,
            'id_user' => Auth::id(),
            'kode_pendapatan' => $request->kode_pendapatan,
            'tanggal_pendapatan' => $request->tanggal_pendapatan,
            'kategori' => $request->kategori,
            'sumber' => $request->sumber,
            'jumlah' => $request->jumlah,
            'metode_terima' => $request->metode_terima,
            'keterangan' => $request->keterangan,
        ];

        if ($request->hasFile('bukti_penerimaan')) {
            $buktiPath = $request->file('bukti_penerimaan')->store('pendapatan_pasif', 'public');
            $data['bukti_penerimaan'] = $buktiPath;
        }

        PendapatanPasif::create($data);

        return redirect()->route('owner.pendapatan_pasif.index')
                       ->with('success', 'PendapatanPasif berhasil ditambahkan');
    }

    public function show($id)
    {
        $pendapatan_pasif = PendapatanPasif::with(['toko', 'user'])->findOrFail($id);
        
        if ($pendapatan_pasif->toko->id_perusahaan != Auth::user()->id_perusahaan) {
            return redirect()->route('owner.pendapatan_pasif.index')
                           ->with('error', 'Anda tidak memiliki akses ke pendapatan_pasif ini');
        }

        return view('owner.pendapatan_pasif.show', compact('pendapatan_pasif'));
    }

    public function edit($id)
    {
        $pendapatan_pasif = PendapatanPasif::findOrFail($id);
        
        if ($pendapatan_pasif->toko->id_perusahaan != Auth::user()->id_perusahaan) {
            return redirect()->route('owner.pendapatan_pasif.index')
                           ->with('error', 'Anda tidak memiliki akses ke pendapatan_pasif ini');
        }

        return view('owner.pendapatan_pasif.edit', compact('pendapatan_pasif'));
    }

    public function update(Request $request, $id)
    {
        $pendapatan_pasif = PendapatanPasif::findOrFail($id);
        
        if ($pendapatan_pasif->toko->id_perusahaan != Auth::user()->id_perusahaan) {
            return redirect()->route('owner.pendapatan_pasif.index')
                           ->with('error', 'Anda tidak memiliki akses ke pendapatan_pasif ini');
        }

        $request->validate([
            'kode_pendapatan' => 'required|max:20|unique:pendapatan_pasif,kode_pendapatan,' . $id . ',id_pendapatan',
            'tanggal_pendapatan' => 'required|date',
            'kategori' => 'required|in:Bunga Bank,Sewa Aset,Komisi,Investasi,Lainnya',
            'sumber' => 'required',
            'jumlah' => 'required|numeric|min:0',
            'metode_terima' => 'required|in:Tunai,Transfer',
            'bukti_penerimaan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'keterangan' => 'nullable',
        ]);

        $data = [
            'kode_pendapatan' => $request->kode_pendapatan,
            'tanggal_pendapatan' => $request->tanggal_pendapatan,
            'kategori' => $request->kategori,
            'sumber' => $request->sumber,
            'jumlah' => $request->jumlah,
            'metode_terima' => $request->metode_terima,
            'keterangan' => $request->keterangan,
        ];

        if ($request->hasFile('bukti_penerimaan')) {
            if ($pendapatan_pasif->bukti_penerimaan && Storage::disk('public')->exists($pendapatan_pasif->bukti_penerimaan)) {
                Storage::disk('public')->delete($pendapatan_pasif->bukti_penerimaan);
            }
            
            $buktiPath = $request->file('bukti_penerimaan')->store('pendapatan_pasif', 'public');
            $data['bukti_penerimaan'] = $buktiPath;
        }

        $pendapatan_pasif->update($data);

        return redirect()->route('owner.pendapatan_pasif.index')
                       ->with('success', 'PendapatanPasif berhasil diupdate');
    }

    public function destroy($id)
    {
        $pendapatan_pasif = PendapatanPasif::findOrFail($id);
        
        if ($pendapatan_pasif->toko->id_perusahaan != Auth::user()->id_perusahaan) {
            return redirect()->route('owner.pendapatan_pasif.index')
                           ->with('error', 'Anda tidak memiliki akses ke pendapatan_pasif ini');
        }

        if ($pendapatan_pasif->bukti_penerimaan && Storage::disk('public')->exists($pendapatan_pasif->bukti_penerimaan)) {
            Storage::disk('public')->delete($pendapatan_pasif->bukti_penerimaan);
        }

        $pendapatan_pasif->delete();
        
        return redirect()->route('owner.pendapatan_pasif.index')
                       ->with('success', 'PendapatanPasif berhasil dihapus');
    }
}
