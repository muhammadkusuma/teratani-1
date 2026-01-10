@extends('layouts.owner')

@section('title', 'Buat Cabang Baru')

@section('content')
    <div class="max-w-3xl mx-auto">

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Buka Cabang Baru</h1>
            <nav class="flex text-sm text-gray-500 mt-1">
                <ol class="flex items-center space-x-2">
                    <li>
                        <a href="{{ route('owner.toko.index') }}" class="hover:text-green-600 hover:underline transition">
                            Daftar Toko
                        </a>
                    </li>
                    <li><span class="text-gray-300">/</span></li>
                    <li class="font-medium text-gray-800">Form Tambah</li>
                </ol>
            </nav>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

            <div class="px-8 py-5 border-b bg-gray-50/50 flex items-center gap-3">
                <div class="bg-green-100 text-green-600 p-2 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-gray-900">Informasi Toko</h2>
                    <p class="text-xs text-gray-500">Lengkapi data identitas toko cabang baru.</p>
                </div>
            </div>

            <div class="p-8">
                @if ($errors->any())
                    <div
                        class="mb-6 bg-red-50 border border-red-100 text-red-600 rounded-xl p-4 text-sm flex items-start gap-3">
                        <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="font-semibold mb-1">Terjadi Kesalahan:</p>
                            <ul class="list-disc list-inside opacity-90">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ route('owner.toko.store') }}" method="POST">
                    @csrf

                    <div class="space-y-6">
                        <div>
                            <label for="nama_toko" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Toko / Cabang <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="nama_toko" name="nama_toko" required value="{{ old('nama_toko') }}"
                                placeholder="Contoh: Teratani Cabang Surabaya"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition outline-none placeholder:text-gray-300">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="kota" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Kota Lokasi
                                </label>
                                <input type="text" id="kota" name="kota" value="{{ old('kota') }}"
                                    placeholder="Nama Kota"
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition outline-none placeholder:text-gray-300">
                            </div>

                            <div>
                                <label for="no_telp" class="block text-sm font-semibold text-gray-700 mb-2">
                                    No. Telepon / WA
                                </label>
                                <div class="relative">
                                    <span class="absolute left-4 top-2.5 text-gray-400 font-medium text-sm">+62</span>
                                    <input type="text" id="no_telp" name="no_telp" value="{{ old('no_telp') }}"
                                        placeholder="812xxxx"
                                        class="w-full pl-12 pr-4 py-2.5 rounded-xl border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition outline-none placeholder:text-gray-300">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-2">
                                Alamat Lengkap
                            </label>
                            <textarea id="alamat" name="alamat" rows="3" placeholder="Jalan, RT/RW, Kecamatan, Kelurahan..."
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition outline-none placeholder:text-gray-300">{{ old('alamat') }}</textarea>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                        <a href="{{ route('owner.toko.index') }}"
                            class="px-5 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-100 hover:text-gray-800 transition">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-green-600 hover:bg-green-700 shadow-md shadow-green-200 transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan Data
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
