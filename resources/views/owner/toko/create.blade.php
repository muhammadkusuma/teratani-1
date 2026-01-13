@extends('layouts.owner')

@section('title', 'Buat Cabang Baru')

@section('content')
    <div style="font-family: Arial, Helvetica, sans-serif; font-size: 11px; max-width: 800px; margin: 0 auto;">

        {{-- 1. BREADCRUMB / NAVIGASI ATAS --}}
        <div class="mb-2 pb-1 border-b border-gray-400 flex justify-between items-end">
            <div>
                <h1 class="font-bold text-lg text-blue-900 uppercase leading-none">Form Tambah Cabang</h1>
                <div class="mt-1 text-gray-500">
                    <a href="{{ route('owner.toko.index') }}" class="text-blue-700 hover:text-red-600 hover:underline">Daftar
                        Toko</a>
                    <span>&gt;</span>
                    <span class="font-bold text-gray-800">Input Baru</span>
                </div>
            </div>
            <div class="text-[10px] text-gray-400">
                Form ID: FRM-ADD-TOKO-V1
            </div>
        </div>

        {{-- 2. ERROR HANDLER (Kotak Merah Klasik) --}}
        @if ($errors->any())
            <div class="mb-4 bg-red-100 border-2 border-red-600 p-2 text-red-900">
                <div class="font-bold border-b border-red-400 mb-1 pb-1">(!) TERJADI KESALAHAN VALIDASI:</div>
                <ul class="list-square list-inside pl-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- 3. CONTAINER FORM (Gaya Window) --}}
        <div class="bg-gray-100 border-2 border-white border-r-gray-500 border-b-gray-500 shadow-[1px_1px_0_0_black]">

            {{-- Header Form --}}
            <div
                class="bg-blue-800 text-white px-2 py-1 font-bold flex justify-between items-center bg-gradient-to-r from-blue-800 to-blue-600">
                <span>ENTRI DATA TOKO</span>
                <span class="cursor-pointer hover:bg-red-600 px-1 border border-white leading-none">x</span>
            </div>

            <div class="p-4">
                <form action="{{ route('owner.toko.store') }}" method="POST">
                    @csrf

                    {{-- GROUP 1: IDENTITAS UTAMA --}}
                    <fieldset class="border border-gray-400 p-3 mb-4 bg-white relative">
                        <legend
                            class="px-1 bg-white text-blue-800 font-bold border border-gray-400 absolute -top-2.5 left-2 text-[10px]">
                            A. IDENTITAS UNIT BISNIS
                        </legend>

                        <div class="grid grid-cols-1 gap-3 mt-1">
                            {{-- Nama Toko --}}
                            <div>
                                <label for="nama_toko" class="block font-bold text-gray-700 mb-1">
                                    Nama Toko / Cabang <span class="text-red-600">*</span>
                                </label>
                                <input type="text" id="nama_toko" name="nama_toko" required
                                    value="{{ old('nama_toko') }}"
                                    class="w-full px-2 py-1 border-2 border-gray-300 border-l-gray-500 border-t-gray-500 bg-yellow-50 focus:bg-white focus:outline-none focus:border-blue-600"
                                    placeholder="Wajib Diisi...">
                            </div>
                        </div>
                    </fieldset>

                    {{-- GROUP 2: LOKASI & KONTAK --}}
                    <fieldset class="border border-gray-400 p-3 mb-4 bg-white relative">
                        <legend
                            class="px-1 bg-white text-blue-800 font-bold border border-gray-400 absolute -top-2.5 left-2 text-[10px]">
                            B. LOKASI & KONTAK
                        </legend>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-1">
                            {{-- Kota --}}
                            <div>
                                <label for="kota" class="block font-bold text-gray-700 mb-1">Kota / Kabupaten</label>
                                <input type="text" id="kota" name="kota" value="{{ old('kota') }}"
                                    class="w-full px-2 py-1 border border-gray-400 shadow-inner focus:outline-none focus:bg-blue-50">
                            </div>

                            {{-- Telepon --}}
                            <div>
                                <label for="no_telp" class="block font-bold text-gray-700 mb-1">No. Telepon / WA</label>
                                <div class="flex">
                                    <span
                                        class="bg-gray-200 border border-gray-400 border-r-0 px-2 py-1 text-gray-600 font-mono">+62</span>
                                    <input type="text" id="no_telp" name="no_telp" value="{{ old('no_telp') }}"
                                        class="w-full px-2 py-1 border border-gray-400 shadow-inner focus:outline-none focus:bg-blue-50">
                                </div>
                            </div>

                            {{-- Alamat (Full Width) --}}
                            <div class="col-span-1 md:col-span-2">
                                <label for="alamat" class="block font-bold text-gray-700 mb-1">Alamat Lengkap</label>
                                <textarea id="alamat" name="alamat" rows="2"
                                    class="w-full px-2 py-1 border border-gray-400 shadow-inner focus:outline-none focus:bg-blue-50 font-mono text-[10px]">{{ old('alamat') }}</textarea>
                                <p class="text-[9px] text-gray-500 mt-0.5">* Masukkan nama jalan, RT/RW, dan Kode Pos.</p>
                            </div>
                            {{-- Info Rekening Pembayaran --}}
                            <div class="col-span-1 md:col-span-2 mt-2">
                                <label for="info_rekening" class="block font-bold text-gray-700 mb-1">
                                    Info Pembayaran / No. Rekening
                                </label>
                                <textarea id="info_rekening" name="info_rekening" rows="2"
                                    placeholder="Contoh: BCA 123456 a.n Toko Tani&#10;BRI 98765 a.n Toko Tani"
                                    class="w-full px-2 py-1 border border-gray-400 shadow-inner focus:outline-none focus:bg-blue-50 font-mono text-[10px]">{{ old('info_rekening') }}</textarea>
                                <p class="text-[9px] text-gray-500 mt-0.5">* Info ini akan muncul di bagian bawah struk dan
                                    faktur.</p>
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
                            class="px-4 py-1 bg-gray-200 text-black border-2 border-white border-r-black border-b-black hover:bg-gray-300 active:border-gray-600 active:border-r-white active:border-b-white text-[11px] font-bold flex items-center justify-center gap-1 w-32 shadow-md">
                            <svg class="w-3 h-3 text-blue-800" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z" />
                            </svg>
                            SIMPAN
                        </button>
                    </div>

                </form>
            </div>

            {{-- Footer Window --}}
            <div class="bg-gray-200 border-t border-gray-400 p-1 text-[9px] text-gray-500">
                Ready.
            </div>
        </div>
    </div>
@endsection
