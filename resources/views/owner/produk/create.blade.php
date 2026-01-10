@extends('layouts.owner')

@section('content')
    <div class="container-fluid px-6 py-6 bg-gray-50 min-h-screen">

        {{-- Header Navigation --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Tambah Produk Baru</h1>
                <p class="text-sm text-gray-500">Toko: <span class="font-semibold text-blue-600">{{ $toko->nama_toko }}</span>
                </p>
            </div>
            <a href="{{ route('owner.toko.produk.index', $toko->id_toko) }}"
                class="text-sm text-gray-600 hover:text-gray-900 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        {{-- Form Container --}}
        <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden max-w-4xl mx-auto">

            {{-- Form Header --}}
            <div class="bg-gray-100 px-6 py-3 border-b border-gray-200">
                <h2 class="text-sm font-bold text-gray-700 uppercase tracking-wide">
                    <i class="fas fa-edit mr-2"></i> Form Input Data
                </h2>
            </div>

            <form action="{{ route('owner.toko.produk.store', $toko->id_toko) }}" method="POST"
                enctype="multipart/form-data" class="p-6">
                @csrf

                {{-- SECTION 1: Informasi Dasar --}}
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-900 border-b pb-2 mb-4">A. Informasi Dasar</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Nama Produk --}}
                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nama Produk <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="nama"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3 @error('nama') border-red-500 @enderror"
                                value="{{ old('nama') }}" placeholder="Contoh: Pupuk NPK" required>
                            @error('nama')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Kategori (DENGAN TOMBOL TAMBAH) --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Kategori <span
                                    class="text-red-500">*</span></label>
                            <div class="flex gap-2">
                                <select name="kategori_id" id="kategori_id"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach ($kategoris as $cat)
                                        <option value="{{ $cat->id_kategori }}"
                                            {{ old('kategori_id') == $cat->id_kategori ? 'selected' : '' }}>
                                            {{ $cat->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" onclick="toggleModal('modalKategori')"
                                    class="bg-blue-600 text-white px-3 rounded-md hover:bg-blue-700 transition"
                                    title="Tambah Kategori Baru">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Satuan (DENGAN TOMBOL TAMBAH) --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Satuan <span
                                    class="text-red-500">*</span></label>
                            <div class="flex gap-2">
                                <select name="satuan_id" id="satuan_id"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3">
                                    <option value="">-- Pilih Satuan --</option>
                                    @foreach ($satuans as $sat)
                                        <option value="{{ $sat->id_satuan }}"
                                            {{ old('satuan_id') == $sat->id_satuan ? 'selected' : '' }}>
                                            {{ $sat->nama_satuan }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" onclick="toggleModal('modalSatuan')"
                                    class="bg-green-600 text-white px-3 rounded-md hover:bg-green-700 transition"
                                    title="Tambah Satuan Baru">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: Harga & Stok --}}
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-900 border-b pb-2 mb-4">B. Harga & Inventaris</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- Harga Beli --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Harga Modal (Beli)</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                </div>
                                <input type="number" name="harga_beli"
                                    class="block w-full rounded-md border-gray-300 pl-10 focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2"
                                    value="{{ old('harga_beli') }}" placeholder="0">
                            </div>
                        </div>

                        {{-- Harga Jual --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Harga Jual <span
                                    class="text-red-500">*</span></label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-gray-500 sm:text-sm font-bold">Rp</span>
                                </div>
                                <input type="number" name="harga_jual"
                                    class="block w-full rounded-md border-gray-300 pl-10 focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 font-semibold"
                                    value="{{ old('harga_jual') }}" placeholder="0" required>
                            </div>
                        </div>

                        {{-- Stok Awal --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Stok Awal <span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="stok"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3"
                                value="0" required>
                        </div>
                    </div>
                </div>

                {{-- SECTION 3: Media --}}
                <div class="mb-8">
                    <h3 class="text-sm font-medium text-gray-900 border-b pb-2 mb-4">C. Media</h3>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Foto Produk</label>
                        <div
                            class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md bg-gray-50 hover:bg-white transition">
                            <div class="space-y-1 text-center">
                                <i class="fas fa-image text-gray-400 text-3xl mb-2"></i>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <label for="file-upload"
                                        class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                        <span>Upload file</span>
                                        <input id="file-upload" name="foto" type="file" class="sr-only">
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-end bg-gray-50 -m-6 mt-0 px-6 py-4 border-t border-gray-200">
                    <button type="reset"
                        class="mr-3 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none">
                        Reset
                    </button>
                    <button type="submit"
                        class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-save mr-2"></i> Simpan Data
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- MODAL TAMBAH KATEGORI --}}
    <div id="modalKategori" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="toggleModal('modalKategori')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Tambah Kategori Baru</h3>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                        <input type="text" id="new_kategori_input" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2" placeholder="Contoh: Makanan">
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="saveKategori()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Simpan
                    </button>
                    <button type="button" onclick="toggleModal('modalKategori')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH SATUAN --}}
    <div id="modalSatuan" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="toggleModal('modalSatuan')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Tambah Satuan Baru</h3>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">Nama Satuan</label>
                        <input type="text" id="new_satuan_input" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm border p-2" placeholder="Contoh: Pcs, Kg">
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="saveSatuan()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Simpan
                    </button>
                    <button type="button" onclick="toggleModal('modalSatuan')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Script JavaScript --}}
    <script>
        // Fungsi Buka Tutup Modal
        function toggleModal(modalID) {
            document.getElementById(modalID).classList.toggle("hidden");
        }

        // --- SETUP AJAX ---
        // CATATAN: Anda harus membuat route di web.php untuk menangani request ini.
        // Route Kategori: Route::post('/api/kategori/store', ...)->name('ajax.kategori.store');
        // Route Satuan: Route::post('/api/satuan/store', ...)->name('ajax.satuan.store');

        // Fungsi Simpan Kategori
        function saveKategori() {
            const nama = document.getElementById('new_kategori_input').value;
            if(!nama) return alert('Nama Kategori tidak boleh kosong');

            // Ganti URL di bawah ini sesuai route Anda
            fetch("{{ route('ajax.kategori.store') }}", { 
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ nama_kategori: nama, toko_id: "{{ $toko->id_toko }}" }) 
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    // Tambah option baru ke select
                    const select = document.getElementById('kategori_id');
                    const option = new Option(data.data.nama_kategori, data.data.id_kategori);
                    select.add(option, undefined);
                    select.value = data.data.id_kategori; // Auto select

                    // Reset & Tutup Modal
                    document.getElementById('new_kategori_input').value = '';
                    toggleModal('modalKategori');
                } else {
                    alert('Gagal menyimpan: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Fungsi Simpan Satuan
        function saveSatuan() {
            const nama = document.getElementById('new_satuan_input').value;
            if(!nama) return alert('Nama Satuan tidak boleh kosong');

            // Ganti URL di bawah ini sesuai route Anda
            fetch("{{ route('ajax.satuan.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ nama_satuan: nama, toko_id: "{{ $toko->id_toko }}" })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    const select = document.getElementById('satuan_id');
                    const option = new Option(data.data.nama_satuan, data.data.id_satuan);
                    select.add(option, undefined);
                    select.value = data.data.id_satuan;

                    document.getElementById('new_satuan_input').value = '';
                    toggleModal('modalSatuan');
                } else {
                    alert('Gagal menyimpan: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
@endsection