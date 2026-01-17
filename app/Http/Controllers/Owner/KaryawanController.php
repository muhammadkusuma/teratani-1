<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        

        $userStores = Toko::where('id_perusahaan', Auth::user()->id_perusahaan)
            ->orderBy('nama_toko')
            ->get();

        

        $query = Karyawan::with('toko')
            ->whereHas('toko', function($q) {
                $q->where('id_perusahaan', Auth::user()->id_perusahaan);
            });

        

        if ($request->filled('id_toko')) {
            $query->where('id_toko', $request->id_toko);
        }

        

        if ($request->filled('status_karyawan')) {
            $query->where('status_karyawan', $request->status_karyawan);
        }

        $karyawans = $query->orderBy('nama_lengkap')->paginate(20);

        return view('owner.karyawan.index', compact('karyawans', 'userStores'));
    }

    public function create()
    {
        

        $userStores = Toko::where('id_perusahaan', Auth::user()->id_perusahaan)
            ->where('is_active', true)
            ->orderBy('nama_toko')
            ->get();

        

        $lastKaryawan = Karyawan::orderBy('id_karyawan', 'desc')->first();
        $nextNumber = $lastKaryawan ? (intval(substr($lastKaryawan->kode_karyawan, 3)) + 1) : 1;
        $kodeKaryawan = 'KRY' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        return view('owner.karyawan.create', compact('userStores', 'kodeKaryawan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_toko'          => 'required|exists:toko,id_toko',
            'kode_karyawan'    => 'required|unique:karyawan,kode_karyawan|max:20',
            'nik'              => 'nullable|max:20',
            'nama_lengkap'     => 'required|max:100',
            'tempat_lahir'     => 'nullable|max:50',
            'tanggal_lahir'    => 'nullable|date',
            'jenis_kelamin'    => 'required|in:L,P',
            'alamat'           => 'nullable',
            'no_hp'            => 'required|max:20',
            'email'            => 'nullable|email|max:100',
            'jabatan'          => 'required|max:50',
            'tanggal_masuk'    => 'required|date',
            'tanggal_keluar'   => 'nullable|date|after:tanggal_masuk',
            'status_karyawan'  => 'required|in:Aktif,Cuti,Resign',
            'gaji_pokok'       => 'nullable|numeric|min:0',
            'foto'             => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'keterangan'       => 'nullable',
        ]);

        $data = [
            'id_toko'          => $request->id_toko,
            'kode_karyawan'    => $request->kode_karyawan,
            'nik'              => $request->nik,
            'nama_lengkap'     => $request->nama_lengkap,
            'tempat_lahir'     => $request->tempat_lahir,
            'tanggal_lahir'    => $request->tanggal_lahir,
            'jenis_kelamin'    => $request->jenis_kelamin,
            'alamat'           => $request->alamat,
            'no_hp'            => $request->no_hp,
            'email'            => $request->email,
            'jabatan'          => $request->jabatan,
            'tanggal_masuk'    => $request->tanggal_masuk,
            'tanggal_keluar'   => $request->tanggal_keluar,
            'status_karyawan'  => $request->status_karyawan,
            'gaji_pokok'       => $request->gaji_pokok ?? 0,
            'keterangan'       => $request->keterangan,
        ];

        

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('karyawan', 'public');
            $data['foto'] = $fotoPath;
        }

        Karyawan::create($data);

        return redirect()->route('owner.karyawan.index')
                       ->with('success', 'Karyawan berhasil ditambahkan');
    }

    public function show($id)
    {
        $karyawan = Karyawan::with('toko')->findOrFail($id);
        
        

        if ($karyawan->toko->id_perusahaan != Auth::user()->id_perusahaan) {
            return redirect()->route('owner.karyawan.index')
                           ->with('error', 'Anda tidak memiliki akses ke karyawan ini');
        }

        return view('owner.karyawan.show', compact('karyawan'));
    }

    public function edit($id)
    {
        $karyawan = Karyawan::findOrFail($id);
        
        

        if ($karyawan->toko->id_perusahaan != Auth::user()->id_perusahaan) {
            return redirect()->route('owner.karyawan.index')
                           ->with('error', 'Anda tidak memiliki akses ke karyawan ini');
        }

        $userStores = Toko::where('id_perusahaan', Auth::user()->id_perusahaan)
            ->where('is_active', true)
            ->orderBy('nama_toko')
            ->get();

        return view('owner.karyawan.edit', compact('karyawan', 'userStores'));
    }

    public function update(Request $request, $id)
    {
        $karyawan = Karyawan::findOrFail($id);
        
        

        if ($karyawan->toko->id_perusahaan != Auth::user()->id_perusahaan) {
            return redirect()->route('owner.karyawan.index')
                           ->with('error', 'Anda tidak memiliki akses ke karyawan ini');
        }

        $request->validate([
            'id_toko'          => 'required|exists:toko,id_toko',
            'kode_karyawan'    => 'required|max:20|unique:karyawan,kode_karyawan,' . $id . ',id_karyawan',
            'nik'              => 'nullable|max:20',
            'nama_lengkap'     => 'required|max:100',
            'tempat_lahir'     => 'nullable|max:50',
            'tanggal_lahir'    => 'nullable|date',
            'jenis_kelamin'    => 'required|in:L,P',
            'alamat'           => 'nullable',
            'no_hp'            => 'required|max:20',
            'email'            => 'nullable|email|max:100',
            'jabatan'          => 'required|max:50',
            'tanggal_masuk'    => 'required|date',
            'tanggal_keluar'   => 'nullable|date|after:tanggal_masuk',
            'status_karyawan'  => 'required|in:Aktif,Cuti,Resign',
            'gaji_pokok'       => 'nullable|numeric|min:0',
            'foto'             => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'keterangan'       => 'nullable',
        ]);

        $data = [
            'id_toko'          => $request->id_toko,
            'kode_karyawan'    => $request->kode_karyawan,
            'nik'              => $request->nik,
            'nama_lengkap'     => $request->nama_lengkap,
            'tempat_lahir'     => $request->tempat_lahir,
            'tanggal_lahir'    => $request->tanggal_lahir,
            'jenis_kelamin'    => $request->jenis_kelamin,
            'alamat'           => $request->alamat,
            'no_hp'            => $request->no_hp,
            'email'            => $request->email,
            'jabatan'          => $request->jabatan,
            'tanggal_masuk'    => $request->tanggal_masuk,
            'tanggal_keluar'   => $request->tanggal_keluar,
            'status_karyawan'  => $request->status_karyawan,
            'gaji_pokok'       => $request->gaji_pokok ?? 0,
            'keterangan'       => $request->keterangan,
        ];

        

        if ($request->hasFile('foto')) {
            

            if ($karyawan->foto && Storage::disk('public')->exists($karyawan->foto)) {
                Storage::disk('public')->delete($karyawan->foto);
            }
            
            $fotoPath = $request->file('foto')->store('karyawan', 'public');
            $data['foto'] = $fotoPath;
        }

        $karyawan->update($data);

        return redirect()->route('owner.karyawan.index')
                       ->with('success', 'Karyawan berhasil diupdate');
    }

    public function destroy($id)
    {
        $karyawan = Karyawan::findOrFail($id);
        
        

        if ($karyawan->toko->id_perusahaan != Auth::user()->id_perusahaan) {
            return redirect()->route('owner.karyawan.index')
                           ->with('error', 'Anda tidak memiliki akses ke karyawan ini');
        }

        

        if ($karyawan->foto && Storage::disk('public')->exists($karyawan->foto)) {
            Storage::disk('public')->delete($karyawan->foto);
        }

        $karyawan->delete();
        
        return redirect()->route('owner.karyawan.index')
                       ->with('success', 'Karyawan berhasil dihapus');
    }
}
