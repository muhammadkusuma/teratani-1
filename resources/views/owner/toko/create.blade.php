@extends('layouts.owner')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Tambah Toko</h1>

        {{-- Breadcrumb --}}
        <nav class="text-sm text-gray-600 mb-6">
            <ol class="flex space-x-2">
                <li>
                    <a href="{{ route('owner.toko.index') }}" class="text-blue-600 hover:underline">
                        Daftar Toko
                    </a>
                </li>
                <li>/</li>
                <li class="text-gray-800 font-medium">Buat Baru</li>
            </ol>
        </nav>

        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 7l9-4 9 4v13a1 1 0 01-1 1H4a1 1 0 01-1-1V7z" />
                    </svg>
                    Form Toko Baru
                </h2>
            </div>

            <div class="p-6">
                {{-- Error Validasi --}}
                @if ($errors->any())
                    <div class="mb-4 rounded-lg border border-red-300 bg-red-100 p-4 text-red-700">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('owner.toko.store') }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- Nama Toko --}}
                    <div>
                        <label for="nama_toko" class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Toko <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nama_toko" name="nama_toko" required value="{{ old('nama_toko') }}"
                            class="w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500 px-4 py-2">
                    </div>

                    {{-- Kota & No Telp --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="kota" class="block text-sm font-medium text-gray-700 mb-1">
                                Kota
                            </label>
                            <input type="text" id="kota" name="kota" value="{{ old('kota') }}"
                                class="w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500 px-4 py-2">
                        </div>

                        <div>
                            <label for="no_telp" class="block text-sm font-medium text-gray-700 mb-1">
                                No. Telepon
                            </label>
                            <input type="text" id="no_telp" name="no_telp" value="{{ old('no_telp') }}"
                                class="w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500 px-4 py-2">
                        </div>
                    </div>

                    {{-- Alamat --}}
                    <div>
                        <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">
                            Alamat Lengkap
                        </label>
                        <textarea id="alamat" name="alamat" rows="3"
                            class="w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500 px-4 py-2">{{ old('alamat') }}</textarea>
                    </div>

                    {{-- Action --}}
                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <a href="{{ route('owner.toko.index') }}"
                            class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300">
                            Batal
                        </a>
                        <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                            Simpan Toko
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
