@extends('layouts.owner')

@section('title', 'Input Produk Baru')

@section('content')
    {{-- Container Utama: Background Abu-abu Windows Klasik --}}
    <div
        style="font-family: 'MS Sans Serif', Arial, sans-serif; font-size: 11px; max-width: 800px; margin: 0 auto; padding-bottom: 50px;">

        {{-- 1. HEADER HALAMAN --}}
        <div class="mb-2 pb-1 border-b border-gray-500 flex justify-between items-end">
            <div>
                <h1 class="font-bold text-lg text-blue-900 uppercase leading-none">Master Barang</h1>
                <div class="mt-1 text-gray-500">
                    <a href="{{ route('owner.toko.produk.index', $toko->id_toko) }}"
                        class="text-blue-700 hover:text-red-600 hover:underline">
                        [ << Kembali ke Daftar ] </a>
                            <span>&gt;</span>
                            <span class="font-bold text-gray-800">Entri Baru</span>
                </div>
            </div>
            <div class="text-right text-[10px] text-gray-500">
                Toko: <b>{{ $toko->nama_toko }}</b>
            </div>
        </div>

        {{-- 2. CONTAINER FORM (Gaya Window) --}}
        <div class="bg-[#d4d0c8] border-2 border-white border-r-gray-600 border-b-gray-600 shadow-[2px_2px_0_0_black]">

            {{-- Header Window --}}
            <div
                class="bg-blue-800 text-white px-2 py-1 font-bold flex justify-between items-center bg-gradient-to-r from-blue-800 to-blue-600">
                <span>FORM INPUT DATA BARANG</span>
                <div class="flex gap-1">
                    <span
                        class="border border-white bg-gray-300 text-black px-1 leading-none text-[9px] cursor-pointer">_</span>
                    <span
                        class="border border-white bg-gray-300 text-black px-1 leading-none text-[9px] cursor-pointer">X</span>
                </div>
            </div>

            <div class="p-3">
                <form action="{{ route('owner.toko.produk.store', $toko->id_toko) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    {{-- ERROR HANDLER --}}
                    @if ($errors->any())
                        <div class="mb-3 bg-white border border-red-600 p-2 text-red-900 overflow-y-auto max-h-20">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="flex flex-col md:flex-row gap-3">

                        {{-- KOLOM KIRI: DATA UTAMA --}}
                        <div class="flex-1">

                            {{-- A. IDENTITAS BARANG --}}
                            <fieldset class="border border-gray-500 p-2 mb-3 relative">
                                <legend
                                    class="px-1 text-blue-800 font-bold absolute -top-2 left-2 text-[10px] bg-[#d4d0c8]">
                                    A. IDENTITAS BARANG
                                </legend>

                                <div class="mt-2 space-y-2">
                                    {{-- Nama Produk --}}
                                    <div>
                                        <label class="block font-bold text-gray-700 mb-0.5">Nama Produk <span
                                                class="text-red-600">*</span></label>
                                        <input type="text" name="nama" value="{{ old('nama') }}" required
                                            placeholder="Contoh: Pupuk NPK..."
                                            class="w-full px-2 py-1 border-2 border-gray-400 border-l-black border-t-black bg-white focus:outline-none focus:bg-yellow-50">
                                    </div>

                                    <div class="grid grid-cols-2 gap-2">
                                        {{-- Kategori --}}
                                        <div>
                                            <label class="block font-bold text-gray-700 mb-0.5">Kategori <span
                                                    class="text-red-600">*</span></label>
                                            <div class="flex gap-1">
                                                <select name="kategori_id" id="kategori_id"
                                                    class="flex-1 px-1 py-1 border-2 border-gray-400 border-l-black border-t-black bg-white">
                                                    <option value="">- Pilih -</option>
                                                    @foreach ($kategoris as $cat)
                                                        <option value="{{ $cat->id_kategori }}"
                                                            {{ old('kategori_id') == $cat->id_kategori ? 'selected' : '' }}>
                                                            {{ $cat->nama_kategori }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                {{-- Tombol Tambah Retro --}}
                                                <button type="button" onclick="toggleModal('modalKategori')"
                                                    title="Tambah Kategori"
                                                    class="w-6 bg-gray-200 border-2 border-white border-r-black border-b-black active:border-t-black active:border-l-black active:border-r-white active:border-b-white font-bold text-blue-800">
                                                    +
                                                </button>
                                            </div>
                                        </div>

                                        {{-- Satuan --}}
                                        <div>
                                            <label class="block font-bold text-gray-700 mb-0.5">Satuan <span
                                                    class="text-red-600">*</span></label>
                                            <div class="flex gap-1">
                                                <select name="satuan_id" id="satuan_id"
                                                    class="flex-1 px-1 py-1 border-2 border-gray-400 border-l-black border-t-black bg-white">
                                                    <option value="">- Pilih -</option>
                                                    @foreach ($satuans as $sat)
                                                        <option value="{{ $sat->id_satuan }}"
                                                            {{ old('satuan_id') == $sat->id_satuan ? 'selected' : '' }}>
                                                            {{ $sat->nama_satuan }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                {{-- Tombol Tambah Retro --}}
                                                <button type="button" onclick="toggleModal('modalSatuan')"
                                                    title="Tambah Satuan"
                                                    class="w-6 bg-gray-200 border-2 border-white border-r-black border-b-black active:border-t-black active:border-l-black active:border-r-white active:border-b-white font-bold text-green-800">
                                                    +
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            {{-- B. HARGA & STOK --}}
                            <fieldset class="border border-gray-500 p-2 relative">
                                <legend
                                    class="px-1 text-blue-800 font-bold absolute -top-2 left-2 text-[10px] bg-[#d4d0c8]">
                                    B. VALUASI & STOK
                                </legend>

                                <div class="mt-2 grid grid-cols-3 gap-2">
                                    {{-- Harga Beli --}}
                                    <div>
                                        <label class="block font-bold text-gray-700 mb-0.5">Hrg. Beli</label>
                                        <input type="number" name="harga_beli" value="{{ old('harga_beli') }}"
                                            placeholder="0"
                                            class="w-full px-2 py-1 border-2 border-gray-400 border-l-black border-t-black bg-white text-right">
                                    </div>

                                    {{-- Harga Jual --}}
                                    <div>
                                        <label class="block font-bold text-gray-700 mb-0.5">Hrg. Jual <span
                                                class="text-red-600">*</span></label>
                                        <input type="number" name="harga_jual" value="{{ old('harga_jual') }}" required
                                            placeholder="0"
                                            class="w-full px-2 py-1 border-2 border-gray-400 border-l-black border-t-black bg-yellow-50 font-bold text-right">
                                    </div>

                                    {{-- Stok --}}
                                    <div>
                                        <label class="block font-bold text-gray-700 mb-0.5">Stok Awal</label>
                                        <input type="number" name="stok" value="0" required
                                            class="w-full px-2 py-1 border-2 border-gray-400 border-l-black border-t-black bg-white text-right">
                                    </div>
                                </div>
                            </fieldset>

                        </div>

                        {{-- KOLOM KANAN: MEDIA & FOTO --}}
                        <div class="w-full md:w-1/3">
                            <fieldset class="border border-gray-500 p-2 h-full relative">
                                <legend
                                    class="px-1 text-blue-800 font-bold absolute -top-2 left-2 text-[10px] bg-[#d4d0c8]">
                                    C. GAMBAR PRODUK
                                </legend>

                                <div
                                    class="mt-2 bg-gray-400 border-2 border-gray-600 border-b-white border-r-white p-1 h-32 flex items-center justify-center mb-2">
                                    <span class="text-gray-200 text-4xl font-bold">IMG</span>
                                </div>

                                <label class="block text-xs mb-1">Pilih File (Max 2MB):</label>
                                <input type="file" name="foto"
                                    class="w-full text-[10px] border border-gray-400 bg-white">
                                <p class="text-[9px] text-gray-600 mt-1">* Format: JPG, PNG, GIF</p>
                            </fieldset>
                        </div>
                    </div>

                    {{-- TOMBOL AKSI (Gaya 3D Windows) --}}
                    <div class="flex items-center justify-end gap-2 pt-3 mt-3 border-t border-gray-400">
                        <button type="reset"
                            class="px-4 py-1 bg-[#d4d0c8] text-black border-2 border-white border-r-black border-b-black hover:bg-gray-300 active:border-gray-600 active:border-r-white active:border-b-white text-[11px] font-bold w-24">
                            RESET
                        </button>

                        <button type="submit"
                            class="px-4 py-1 bg-[#d4d0c8] text-black border-2 border-white border-r-black border-b-black hover:bg-gray-300 active:border-gray-600 active:border-r-white active:border-b-white text-[11px] font-bold flex items-center justify-center gap-1 w-32">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            SIMPAN
                        </button>
                    </div>

                </form>
            </div>

            {{-- Status Bar Window --}}
            <div class="bg-[#d4d0c8] border-t border-gray-400 p-1 text-[9px] text-gray-600 inset-x-0">
                Tekan [Enter] untuk pindah kolom, [Simpan] untuk memproses.
            </div>
        </div>
    </div>

    {{-- MODAL RETRO: KATEGORI --}}
    <div id="modalKategori"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
        <div class="bg-[#d4d0c8] w-80 border-2 border-white border-r-black border-b-black shadow-2xl">
            <div class="bg-blue-800 text-white px-2 py-0.5 font-bold flex justify-between items-center text-[11px]">
                <span>Input Kategori Baru</span>
                <button onclick="toggleModal('modalKategori')"
                    class="text-white hover:bg-red-600 px-1 font-bold">X</button>
            </div>
            <div class="p-3">
                <label class="block mb-1 font-bold">Nama Kategori:</label>
                <input type="text" id="new_kategori_input"
                    class="w-full px-2 py-1 border-2 border-gray-400 border-l-black border-t-black mb-3 focus:outline-none">

                <div class="flex justify-end gap-2">
                    <button onclick="toggleModal('modalKategori')"
                        class="px-3 py-1 bg-[#d4d0c8] border-2 border-white border-r-black border-b-black active:border-t-black font-bold">Batal</button>
                    <button onclick="saveKategori()"
                        class="px-3 py-1 bg-[#d4d0c8] border-2 border-white border-r-black border-b-black active:border-t-black font-bold">OK</button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL RETRO: SATUAN --}}
    <div id="modalSatuan" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
        <div class="bg-[#d4d0c8] w-80 border-2 border-white border-r-black border-b-black shadow-2xl">
            <div class="bg-blue-800 text-white px-2 py-0.5 font-bold flex justify-between items-center text-[11px]">
                <span>Input Satuan Baru</span>
                <button onclick="toggleModal('modalSatuan')" class="text-white hover:bg-red-600 px-1 font-bold">X</button>
            </div>
            <div class="p-3">
                <label class="block mb-1 font-bold">Nama Satuan:</label>
                <input type="text" id="new_satuan_input"
                    class="w-full px-2 py-1 border-2 border-gray-400 border-l-black border-t-black mb-3 focus:outline-none">

                <div class="flex justify-end gap-2">
                    <button onclick="toggleModal('modalSatuan')"
                        class="px-3 py-1 bg-[#d4d0c8] border-2 border-white border-r-black border-b-black active:border-t-black font-bold">Batal</button>
                    <button onclick="saveSatuan()"
                        class="px-3 py-1 bg-[#d4d0c8] border-2 border-white border-r-black border-b-black active:border-t-black font-bold">OK</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Script JavaScript (Logika tetap sama) --}}
    <script>
        function toggleModal(modalID) {
            document.getElementById(modalID).classList.toggle("hidden");
        }

        function saveKategori() {
            const nama = document.getElementById('new_kategori_input').value;
            if (!nama) return alert('Nama Kategori tidak boleh kosong');

            // Pastikan route ini ada di web.php
            fetch("{{ route('ajax.kategori.store') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        nama_kategori: nama,
                        toko_id: "{{ $toko->id_toko }}"
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const select = document.getElementById('kategori_id');
                        const option = new Option(data.data.nama_kategori, data.data.id_kategori);
                        select.add(option, undefined);
                        select.value = data.data.id_kategori;
                        document.getElementById('new_kategori_input').value = '';
                        toggleModal('modalKategori');
                    } else {
                        alert('Gagal: ' + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function saveSatuan() {
            const nama = document.getElementById('new_satuan_input').value;
            if (!nama) return alert('Nama Satuan tidak boleh kosong');

            fetch("{{ route('ajax.satuan.store') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        nama_satuan: nama,
                        toko_id: "{{ $toko->id_toko }}"
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const select = document.getElementById('satuan_id');
                        const option = new Option(data.data.nama_satuan, data.data.id_satuan);
                        select.add(option, undefined);
                        select.value = data.data.id_satuan;
                        document.getElementById('new_satuan_input').value = '';
                        toggleModal('modalSatuan');
                    } else {
                        alert('Gagal: ' + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
@endsection
