<?php
namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengeluaranController extends Controller
{
    public function index()
    {
        // 1. Ambil ID Toko dari Session yang BENAR ('toko_active_id')
        $id_toko = session('toko_active_id');

        // 2. Validasi: Jika belum pilih toko, redirect atau tampilkan kosong
        if (! $id_toko) {
            return redirect()->route('owner.dashboard')
                ->with('error', 'Silakan pilih toko terlebih dahulu untuk melihat pengeluaran.');
        }

        $pengeluaran = Pengeluaran::where('id_toko', $id_toko)
            ->orderBy('tgl_pengeluaran', 'desc')
            ->paginate(10);

        return view('owner.pengeluaran.index', compact('pengeluaran'));
    }

    public function create()
    {
        // Cek apakah toko sudah dipilih sebelum masuk form
        if (! session('toko_active_id')) {
            return redirect()->route('owner.dashboard')
                ->with('error', 'Silakan pilih toko aktif terlebih dahulu.');
        }

        return view('owner.pengeluaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tgl_pengeluaran' => 'required|date',
            'kategori_biaya'  => 'required|string',
            'nominal'         => 'required|numeric|min:0',
            'bukti_foto'      => 'nullable|image|max:2048',
        ]);

        // 1. Ambil ID Toko dari Session yang BENAR
        $id_toko = session('toko_active_id');

        // 2. Validasi Kritis: Pastikan ID Toko tidak null
        if (! $id_toko) {
            return redirect()->route('owner.dashboard')
                ->with('error', 'Gagal menyimpan: Toko tidak terdeteksi. Silakan pilih toko kembali.');
        }

        $data            = $request->all();
        $data['id_toko'] = $id_toko; // Masukkan ID toko yang valid
        $data['id_user'] = Auth::id();

        // Handle Upload Foto
        if ($request->hasFile('bukti_foto')) {
            $path               = $request->file('bukti_foto')->store('pengeluaran', 'public');
            $data['bukti_foto'] = $path;
        }

        Pengeluaran::create($data);

        return redirect()->route('owner.pengeluaran.index')
            ->with('success', 'Data pengeluaran berhasil disimpan.');
    }

    public function destroy($id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);

        // Opsional: Pastikan pengeluaran ini milik toko yang sedang aktif (Security)
        if ($pengeluaran->id_toko != session('toko_active_id')) {
            abort(403, 'Akses ditolak.');
        }

        if ($pengeluaran->bukti_foto && Storage::disk('public')->exists($pengeluaran->bukti_foto)) {
            Storage::disk('public')->delete($pengeluaran->bukti_foto);
        }

        $pengeluaran->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }
}
