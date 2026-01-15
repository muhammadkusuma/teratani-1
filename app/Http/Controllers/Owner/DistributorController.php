<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Distributor;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        ]);

        Distributor::create([
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

        return redirect()->route('owner.distributor.index')
                       ->with('success', 'Distributor berhasil ditambahkan');
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
        ]);

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

        return redirect()->route('owner.distributor.index')
                       ->with('success', 'Distributor berhasil diupdate');
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
}
