@extends('layouts.owner')

@section('title', 'Edit Cabang Toko')

@section('content')
    <div style="font-family: Arial, Helvetica, sans-serif; font-size: 11px; max-width: 800px; margin: 0 auto;">

        {{-- 1. BREADCRUMB / NAVIGASI ATAS --}}
        <div class="mb-2 pb-1 border-b border-gray-400 flex justify-between items-end">
            <div>
                <h1 class="font-bold text-lg text-orange-800 uppercase leading-none">Koreksi Data Cabang</h1>
                <div class="mt-1 text-gray-500">
                    <a href="{{ route('owner.toko.index') }}" class="text-blue-700 hover:text-red-600 hover:underline">Daftar
                        Toko</a>
                    <span>&gt;</span>
                    <span class="font-bold text-gray-800">Edit: {{ $toko->kode_toko ?? 'N/A' }}</span>
                </div>
            </div>
            <div class="text-right text-[10px] text-gray-500">
                <div>Last Update: {{ $toko->updated_at ? $toko->updated_at->format('d/m/Y H:i') : '-' }}</div>
                <div>Mode: <b>EDIT / UPDATE</b></div>
            </div>
        </div>

        {{-- 2. ERROR HANDLER --}}
        @if ($errors->any())
            <div class="mb-4 bg-red-100 border-2 border-red-600 p-2 text-red-900">
                <div class="font-bold border-b border-red-400 mb-1 pb-1">(!) GAGAL MENYIMPAN PERUBAHAN:</div>
                <ul class="list-square list-inside pl-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- 3. CONTAINER FORM (Gaya Window) --}}
        <div class="bg-gray-100 border-2 border-white border-r-gray-500 border-b-gray-500 shadow-[1px_1px_0_0_black]">

            {{-- Header Form (Warna Orange untuk membedakan dengan Input Baru) --}}
            <div class="bg-orange-700 text-white px-2 py-1 font-bold flex justify-between items-center">
                <span>FORM PERUBAHAN DATA</span>
                <span class="cursor-pointer hover:bg-red-600 px-1 border border-white leading-none">x</span>
            </div>

            <div class="p-4">
                <form action="{{ route('owner.toko.update', $toko->id_toko) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- GROUP 1: IDENTITAS UTAMA --}}
                    <fieldset class="border border-gray-400 p-3 mb-4 bg-white relative">
                        <legend
                            class="px-1 bg-white text-orange-800 font-bold border border-gray-400 absolute -top-2.5 left-2 text-[10px]">
                            A. IDENTITAS & STATUS
                        </legend>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-1">
                            {{-- Nama Toko --}}
                            <div>
                                <label for="nama_toko" class="block font-bold text-gray-700 mb-1">
                                    Nama Toko / Cabang <span class="text-red-600">*</span>
                                </label>
                                <input type="text" id="nama_toko" name="nama_toko" required
                                    value="{{ old('nama_toko', $toko->nama_toko) }}"
                                    class="w-full px-2 py-1 border-2 border-gray-300 border-l-gray-500 border-t-gray-500 bg-yellow-50 focus:bg-white focus:outline-none focus:border-blue-600">
                            </div>

                            {{-- Status Operasional (Select Box Jadul pengganti Toggle) --}}
                            <div>
                                <label for="is_active" class="block font-bold text-gray-700 mb-1">Status Operasional</label>
                                <select name="is_active" id="is_active"
                                    class="w-full px-2 py-1 border border-gray-400 bg-gray-100 focus:outline-none focus:bg-white">
                                    <option value="1" {{ $toko->is_active == 1 ? 'selected' : '' }}>[ v ] AKTIF / BUKA
                                    </option>
                                    <option value="0" {{ $toko->is_active == 0 ? 'selected' : '' }}>[ x ] NON-AKTIF /
                                        TUTUP</option>
                                </select>
                            </div>
                        </div>
                    </fieldset>

                    {{-- GROUP 2: LOKASI & KONTAK --}}
                    <fieldset class="border border-gray-400 p-3 mb-4 bg-white relative">
                        <legend
                            class="px-1 bg-white text-orange-800 font-bold border border-gray-400 absolute -top-2.5 left-2 text-[10px]">
                            B. LOKASI & KONTAK
                        </legend>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-1">
                            {{-- Kota --}}
                            <div>
                                <label for="kota" class="block font-bold text-gray-700 mb-1">Kota</label>
                                <input type="text" id="kota" name="kota" value="{{ old('kota', $toko->kota) }}"
                                    class="w-full px-2 py-1 border border-gray-400 shadow-inner focus:outline-none focus:bg-blue-50">
                            </div>

                            {{-- Telepon --}}
                            <div>
                                <label for="no_telp" class="block font-bold text-gray-700 mb-1">No. Telepon / WA</label>
                                <input type="text" id="no_telp" name="no_telp"
                                    value="{{ old('no_telp', $toko->no_telp) }}"
                                    class="w-full px-2 py-1 border border-gray-400 shadow-inner focus:outline-none focus:bg-blue-50">
                            </div>

                            {{-- Alamat --}}
                            <div class="col-span-1 md:col-span-2">
                                <label for="alamat" class="block font-bold text-gray-700 mb-1">Alamat Lengkap</label>
                                <textarea id="alamat" name="alamat" rows="2"
                                    class="w-full px-2 py-1 border border-gray-400 shadow-inner focus:outline-none focus:bg-blue-50 font-mono text-[10px]">{{ old('alamat', $toko->alamat) }}</textarea>
                            </div>
                        </div>
                    </fieldset>

                    {{-- ACTION BUTTONS --}}
                    <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-300">
                        {{-- Tombol Batal --}}
                        <a href="{{ route('owner.toko.index') }}"
                            class="px-4 py-1 bg-gray-200 text-black border-2 border-white border-r-gray-600 border-b-gray-600 hover:bg-gray-300 active:border-gray-600 active:border-r-white active:border-b-white text-[11px] font-bold no-underline text-center w-24">
                            BATAL
                        </a>

                        {{-- Tombol Simpan --}}
                        <button type="submit"
                            class="px-4 py-1 bg-gray-200 text-black border-2 border-white border-r-black border-b-black hover:bg-gray-300 active:border-gray-600 active:border-r-white active:border-b-white text-[11px] font-bold flex items-center justify-center gap-1 min-w-[140px] shadow-md">
                            <svg class="w-3 h-3 text-green-700" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z" />
                            </svg>
                            UPDATE DATA
                        </button>
                    </div>

                </form>
            </div>

            {{-- Footer Window --}}
            <div class="bg-gray-200 border-t border-gray-400 p-1 text-[9px] text-gray-500 flex justify-between">
                <span>Record ID: {{ $toko->id_toko }}</span>
                <span>Status: Ready to edit.</span>
            </div>
        </div>
    </div>
@endsection
