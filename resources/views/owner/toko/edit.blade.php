@extends('layouts.owner')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Edit Toko</h1>

        {{-- Breadcrumb --}}
        <nav class="text-sm text-gray-600 mb-6">
            <ol class="flex space-x-2">
                <li>
                    <a href="{{ route('owner.toko.index') }}" class="text-blue-600 hover:underline">
                        Daftar Toko
                    </a>
                </li>
                <li>/</li>
                <li class="text-gray-800 font-medium">Edit Data</li>
            </ol>
        </nav>

        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5h2m-1-1v2m-6 7h12M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Edit Toko: {{ $toko->nama_toko }}
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

                <form action="{{ route('owner.toko.update', $toko->id_toko) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    {{-- Nama Toko --}}
                    <div>
                        <label for="nama_toko" class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Toko <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nama_toko" name="nama_toko" required
                            value="{{ old('nama_toko', $toko->nama_toko) }}"
                            class="w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500 px-4 py-2">
                    </div>

                    {{-- Kota & No Telp --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="kota" class="block text-sm font-medium text-gray-700 mb-1">
                                Kota
                            </label>
                            <input type="text" id="kota" name="kota" value="{{ old('kota', $toko->kota) }}"
                                class="w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500 px-4 py-2">
                        </div>

                        <div>
                            <label for="no_telp" class="block text-sm font-medium text-gray-700 mb-1">
                                No. Telepon
                            </label>
                            <input type="text" id="no_telp" name="no_telp" value="{{ old('no_telp', $toko->no_telp) }}"
                                class="w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500 px-4 py-2">
                        </div>
                    </div>

                    {{-- Alamat --}}
                    <div>
                        <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">
                            Alamat Lengkap
                        </label>
                        <textarea id="alamat" name="alamat" rows="3"
                            class="w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500 px-4 py-2">{{ old('alamat', $toko->alamat) }}</textarea>
                    </div>

                    {{-- Status Aktif --}}
                    <div class="flex items-center gap-3">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" id="is_active" name="is_active" value="1"
                            {{ $toko->is_active ? 'checked' : '' }}
                            class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="is_active" class="text-sm text-gray-700">
                            Status Aktif
                        </label>
                    </div>

                    {{-- Action --}}
                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <a href="{{ route('owner.toko.index') }}"
                            class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300">
                            Batal
                        </a>
                        <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                            Update Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
