@extends('layouts.owner')

@section('title', 'Tambah Distributor')

@section('content')
    <div class="max-w-3xl">
        {{-- Header Style Klasik --}}
        <div class="flex justify-between items-center mb-4">
            <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4">INPUT DATA DISTRIBUTOR BARU</h2>
            <a href="{{ route('owner.distributor.index') }}"
                class="text-blue-700 underline text-xs hover:text-blue-500">&laquo;
                Kembali</a>
        </div>

        {{-- Form Container --}}
        <form action="{{ route('owner.distributor.store') }}" method="POST"
            class="bg-gray-100 p-4 border border-gray-400 shadow-inner">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- Nama Distributor (Full Width) --}}
                <div class="md:col-span-2">
                    <label class="block font-bold text-xs mb-1">Nama Distributor <span class="text-red-600">*</span></label>
                    <input type="text" name="nama_distributor" value="{{ old('nama_distributor') }}" required
                        class="w-full border border-gray-400 p-1 text-sm focus:outline-none focus:border-blue-600"
                        placeholder="Contoh: PT. Sayur Segar Jaya">
                    @error('nama_distributor')
                        <span class="text-[10px] text-red-600 italic block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Nama Kontak --}}
                <div>
                    <label class="block font-bold text-xs mb-1">Nama Kontak (Sales/PIC)</label>
                    <input type="text" name="nama_kontak" value="{{ old('nama_kontak') }}"
                        class="w-full border border-gray-400 p-1 text-sm focus:outline-none focus:border-blue-600"
                        placeholder="Contoh: Budi Santoso">
                </div>

                {{-- No Telepon --}}
                <div>
                    <label class="block font-bold text-xs mb-1">No. Telepon / WhatsApp</label>
                    <input type="text" name="no_telp" value="{{ old('no_telp') }}"
                        class="w-full border border-gray-400 p-1 text-sm focus:outline-none focus:border-blue-600"
                        placeholder="Contoh: 081234567890">
                </div>

                {{-- Alamat (Full Width) --}}
                <div class="md:col-span-2">
                    <label class="block font-bold text-xs mb-1">Alamat Lengkap</label>
                    <textarea name="alamat" rows="3"
                        class="w-full border border-gray-400 p-1 text-sm focus:outline-none focus:border-blue-600"
                        placeholder="Masukkan alamat lengkap distributor...">{{ old('alamat') }}</textarea>
                </div>

            </div>

            {{-- Tombol Aksi --}}
            <div class="mt-4 border-t border-gray-300 pt-3 text-right">
                <button type="submit"
                    class="bg-blue-800 text-white px-4 py-2 border border-blue-900 shadow hover:bg-blue-700 font-bold text-xs uppercase">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
@endsection
