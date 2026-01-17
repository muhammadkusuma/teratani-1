<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PerusahaanController extends Controller
{
    public function index()
    {
        

        $perusahaan = Auth::user()->perusahaan;
        
        if (!$perusahaan) {
            return redirect()->back()->with('error', 'Anda belum terdaftar di perusahaan manapun');
        }
        
        

        $perusahaan->load('tokos');
        
        return view('owner.perusahaan.index', compact('perusahaan'));
    }

    public function edit()
    {
        $perusahaan = Auth::user()->perusahaan;
        
        if (!$perusahaan) {
            return redirect()->route('owner.perusahaan.index')
                           ->with('error', 'Anda belum terdaftar di perusahaan manapun');
        }
        
        return view('owner.perusahaan.edit', compact('perusahaan'));
    }

    public function update(Request $request)
    {
        $perusahaan = Auth::user()->perusahaan;
        
        if (!$perusahaan) {
            return redirect()->route('owner.perusahaan.index')
                           ->with('error', 'Anda belum terdaftar di perusahaan manapun');
        }

        $request->validate([
            'nama_perusahaan' => 'required|max:150',
            'pemilik'         => 'nullable|string|max:150',
            'alamat'          => 'nullable',
            'kota'            => 'nullable|max:50',
            'provinsi'        => 'nullable|max:50',
            'kode_pos'        => 'nullable|max:10',
            'no_telp'         => 'nullable|max:20',
            'email'           => 'nullable|email|max:100',
            'website'         => 'nullable|max:100',
            'npwp'            => 'nullable|max:30',
            'logo'            => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'nama_perusahaan' => $request->nama_perusahaan,
            'pemilik'         => $request->pemilik,
            'alamat'          => $request->alamat,
            'kota'            => $request->kota,
            'provinsi'        => $request->provinsi,
            'kode_pos'        => $request->kode_pos,
            'no_telp'         => $request->no_telp,
            'email'           => $request->email,
            'website'         => $request->website,
            'npwp'            => $request->npwp,
        ];

        

        if ($request->hasFile('logo')) {
            

            if ($perusahaan->logo && Storage::disk('public')->exists($perusahaan->logo)) {
                Storage::disk('public')->delete($perusahaan->logo);
            }
            
            $logoPath = $request->file('logo')->store('logos', 'public');
            $data['logo'] = $logoPath;
        }

        $perusahaan->update($data);

        return redirect()->route('owner.perusahaan.index')
                       ->with('success', 'Data perusahaan berhasil diupdate');
    }
}
