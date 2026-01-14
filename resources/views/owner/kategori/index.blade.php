@extends('layouts.owner')

@section('title', 'Master Data Kategori')

@section('content')
    <div x-data="{ openModal: false, editMode: false, formAction: '', kategoriId: '', namaKategori: '' }">

        
        <div class="flex justify-between items-center mb-3">
            <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4">MASTER DATA KATEGORI</h2>
            <button
                @click="openModal = true; editMode = false; formAction = '{{ route('owner.kategori.store') }}'; namaKategori = ''"
                class="px-3 py-1 bg-blue-700 text-white border border-blue-900 shadow hover:bg-blue-600 text-xs font-bold">
                + TAMBAH KATEGORI
            </button>
        </div>

        
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-2 py-1 mb-2 text-xs">
                {{ session('success') }}
            </div>
        @endif

        
        <div class="overflow-x-auto border border-gray-400 bg-white">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-200 text-gray-700 text-xs uppercase">
                        <th class="border border-gray-400 p-2 text-center w-12">No</th>
                        <th class="border border-gray-400 p-2">Nama Kategori</th>
                        <th class="border border-gray-400 p-2 text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kategori as $item)
                        <tr class="hover:bg-yellow-50 text-xs">
                            <td class="border border-gray-300 p-2 text-center">{{ $loop->iteration }}</td>
                            <td class="border border-gray-300 p-2 font-semibold">{{ $item->nama_kategori }}</td>
                            <td class="border border-gray-300 p-2 text-center">
                                <div class="flex justify-center gap-1">
                                    
                                    <button
                                        @click="
                                            openModal = true; 
                                            editMode = true; 
                                            formAction = '{{ route('owner.kategori.update', $item->id_kategori) }}';
                                            namaKategori = '{{ $item->nama_kategori }}'
                                        "
                                        class="bg-yellow-100 border border-yellow-400 px-2 py-0.5 text-[10px] hover:bg-yellow-200 text-yellow-800 font-bold">
                                        EDIT
                                    </button>

                                    
                                    <form action="{{ route('owner.kategori.destroy', $item->id_kategori) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus kategori ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-100 border border-red-400 px-2 py-0.5 text-[10px] hover:bg-red-200 text-red-800 font-bold">
                                            HAPUS
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-4 text-center text-gray-500 italic border border-gray-300">
                                Belum ada data kategori.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        
        <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
            style="display: none;" x-transition>
            <div class="bg-white w-full max-w-sm p-4 shadow-lg border border-gray-400">
                <h3 class="text-sm font-bold mb-3 border-b border-gray-300 pb-2 uppercase text-gray-700"
                    x-text="editMode ? 'Edit Kategori' : 'Tambah Kategori Baru'"></h3>

                <form :action="formAction" method="POST">
                    @csrf
                    <input type="hidden" name="_method" :value="editMode ? 'PUT' : 'POST'">

                    <div class="mb-3">
                        <label class="block text-gray-700 text-xs font-bold mb-1">NAMA KATEGORI</label>
                        <input type="text" name="nama_kategori" x-model="namaKategori"
                            class="w-full border border-gray-400 p-1.5 text-xs focus:outline-none focus:border-blue-600 bg-gray-50"
                            required placeholder="Masukkan nama kategori...">
                    </div>

                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button" @click="openModal = false"
                            class="bg-gray-200 hover:bg-gray-300 text-gray-800 border border-gray-400 px-3 py-1 text-xs font-bold">
                            BATAL
                        </button>
                        <button type="submit"
                            class="bg-blue-700 hover:bg-blue-800 text-white border border-blue-900 px-3 py-1 text-xs font-bold shadow">
                            SIMPAN
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
