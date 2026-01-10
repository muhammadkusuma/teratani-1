@extends('layouts.owner')

@section('title', 'Pilih Cabang Toko')

@section('content')
    <div class="max-w-7xl mx-auto">

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Pilih Cabang Toko</h1>
                <p class="text-gray-500 text-sm mt-1">
                    Silakan pilih unit bisnis/toko untuk masuk ke dashboard operasional.
                </p>
            </div>

            <a href="{{ route('owner.toko.create') }}"
                class="inline-flex items-center justify-center gap-2 bg-white border border-gray-300 text-gray-700 hover:text-green-600 hover:border-green-600 px-5 py-2.5 rounded-xl text-sm font-medium transition shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Buka Cabang Baru
            </a>
        </div>

        @if (session('info'))
            <div class="mb-6 bg-blue-50 text-blue-800 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('info') }}
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($toko as $item)
                <div
                    class="relative group bg-white rounded-2xl p-6 shadow-sm border border-gray-200 hover:shadow-lg hover:border-green-500 hover:-translate-y-1 transition-all duration-300 cursor-pointer">

                    <a href="{{ route('owner.toko.select', $item->id_toko) }}" class="block text-center">

                        <div class="absolute top-4 right-4">
                            @if ($item->is_active)
                                <span class="flex h-3 w-3">
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                                </span>
                            @else
                                <span class="inline-block h-3 w-3 rounded-full bg-gray-300"></span>
                            @endif
                        </div>

                        <div
                            class="w-20 h-20 mx-auto bg-green-50 text-green-600 rounded-full flex items-center justify-center mb-4 group-hover:bg-green-600 group-hover:text-white transition-colors duration-300 shadow-inner">
                            @if ($item->is_pusat)
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            @else
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            @endif
                        </div>

                        <h3 class="text-xl font-bold text-gray-900 group-hover:text-green-700 transition-colors">
                            {{ $item->nama_toko }}
                        </h3>

                        <p class="text-sm text-gray-500 mt-1 mb-6">
                            {{ $item->kota }} <span class="mx-1">&bull;</span> <span
                                class="font-mono text-xs bg-gray-100 px-2 py-0.5 rounded">{{ $item->kode_toko }}</span>
                        </p>

                        <div
                            class="w-full py-2.5 rounded-xl bg-gray-50 text-gray-600 font-semibold text-sm group-hover:bg-green-600 group-hover:text-white transition-colors shadow-sm">
                            Masuk Dashboard
                        </div>
                    </a>

                    <div
                        class="mt-5 pt-4 border-t border-dashed border-gray-200 flex items-center justify-between text-xs text-gray-400">
                        <span class="flex items-center gap-1 truncate max-w-[50%]">
                            <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            {{ $item->no_telp }}
                        </span>

                        <div class="flex gap-3 z-10 relative">
                            <a href="{{ route('owner.toko.edit', $item->id_toko) }}"
                                class="hover:text-yellow-600 transition flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </a>

                            @if (!$item->is_pusat)
                                <form action="{{ route('owner.toko.destroy', $item->id_toko) }}" method="POST"
                                    onsubmit="return confirm('Hapus toko ini?')" class="inline-block">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="hover:text-red-600 transition flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                </div>
            @empty
                <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-dashed border-gray-300">
                    <div class="inline-flex bg-gray-50 p-4 rounded-full mb-4 text-gray-400">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Belum Ada Toko</h3>
                    <p class="text-gray-500 mb-6 mt-2 max-w-sm mx-auto">
                        Anda belum memiliki cabang toko. Silakan buat toko pertama Anda untuk memulai manajemen stok dan
                        kasir.
                    </p>
                    <a href="{{ route('owner.toko.create') }}"
                        class="inline-flex bg-green-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-green-700 transition shadow-lg shadow-green-200">
                        Buat Toko Sekarang
                    </a>
                </div>
            @endforelse
        </div>
    </div>
@endsection
