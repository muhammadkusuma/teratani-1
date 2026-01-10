@extends('layouts.owner')

@section('title', 'Pilih Cabang Toko')

@section('content')
    {{-- Container Utama --}}
    <div style="font-family: Arial, sans-serif; font-size: 11px;">

        {{-- 1. HEADER HALAMAN --}}
        <div class="border-b-2 border-gray-500 pb-2 mb-4 flex justify-between items-end">
            <div>
                <h1 class="text-lg font-bold text-blue-900 uppercase">Manajemen Unit Bisnis</h1>
                <p class="text-gray-600">Silakan pilih database toko untuk memulai operasional.</p>
            </div>
            
            {{-- Tombol Gaya Klasik --}}
            <a href="{{ route('owner.toko.create') }}"
               class="bg-gray-100 text-black border border-black px-3 py-1 hover:bg-white hover:text-blue-800 shadow-[2px_2px_0px_0px_rgba(0,0,0,0.5)] active:shadow-none active:translate-y-[1px] font-bold flex items-center gap-1">
                <span class="text-green-700 font-bold">[+]</span> Buka Cabang Baru
            </a>
        </div>

        {{-- 2. NOTIFIKASI SYSTEM --}}
        @if (session('info'))
            <div class="mb-4 bg-yellow-50 border border-blue-500 text-blue-900 p-2 flex items-center gap-2">
                <span class="font-bold bg-blue-500 text-white px-1">INFO</span>
                {{ session('info') }}
            </div>
        @endif

        {{-- 3. GRID DAFTAR TOKO --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @forelse($toko as $item)
                {{-- ITEM CARD (Kotak Tegas) --}}
                <div class="bg-gray-100 border border-black p-1 shadow-sm group hover:bg-blue-50">
                    
                    {{-- Card Header: Nama Toko & Status --}}
                    <div class="{{ $item->is_pusat ? 'bg-blue-800' : 'bg-gray-600' }} text-white px-2 py-1 font-bold flex justify-between items-center mb-1">
                        <span class="truncate">{{ $item->kode_toko }}</span>
                        @if ($item->is_active)
                            <span class="text-[9px] bg-green-400 text-black px-1 border border-black">ON</span>
                        @else
                            <span class="text-[9px] bg-red-400 text-black px-1 border border-black">OFF</span>
                        @endif
                    </div>

                    {{-- Card Body --}}
                    <div class="bg-white border border-gray-400 p-2 h-24 relative">
                        <h3 class="font-bold text-sm text-black mb-1 truncate" title="{{ $item->nama_toko }}">
                            {{ $item->nama_toko }}
                        </h3>
                        
                        <div class="text-gray-600 space-y-0.5">
                            <div class="flex gap-1">
                                <span class="w-10">Tipe</span>
                                <span>: {{ $item->is_pusat ? 'PUSAT' : 'CABANG' }}</span>
                            </div>
                            <div class="flex gap-1">
                                <span class="w-10">Kota</span>
                                <span class="truncate">: {{ $item->kota }}</span>
                            </div>
                            <div class="flex gap-1">
                                <span class="w-10">Telp</span>
                                <span>: {{ $item->no_telp }}</span>
                            </div>
                        </div>

                        {{-- Ikon Background Transparan (Dekorasi Tipis) --}}
                        <div class="absolute bottom-1 right-1 opacity-10 pointer-events-none">
                            <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 01-1 1h-2a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4z"/>
                            </svg>
                        </div>
                    </div>

                    {{-- Card Action Footer --}}
                    <div class="mt-1 flex items-center justify-between px-1">
                        {{-- Link Aksi Kecil --}}
                        <div class="flex gap-2">
                            <a href="{{ route('owner.toko.edit', $item->id_toko) }}" class="text-blue-800 hover:text-red-600 hover:underline font-bold text-[10px]">[ Edit ]</a>
                            
                            @if (!$item->is_pusat)
                                <form action="{{ route('owner.toko.destroy', $item->id_toko) }}" method="POST" onsubmit="return confirm('Hapus database toko ini? Data tidak bisa kembali.')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-800 hover:text-red-600 hover:underline font-bold text-[10px] bg-transparent border-0 p-0 cursor-pointer">[ Hapus ]</button>
                                </form>
                            @endif
                        </div>

                        {{-- Tombol Masuk Utama --}}
                        <a href="{{ route('owner.toko.select', $item->id_toko) }}" 
                           class="bg-gray-200 border border-gray-500 hover:bg-blue-100 text-blue-900 px-2 py-0.5 text-[10px] font-bold shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] active:shadow-none active:translate-y-[1px]">
                            KELOLA >>
                        </a>
                    </div>
                </div>

            @empty
                {{-- Empty State (Tampilan Error Box) --}}
                <div class="col-span-full p-8 text-center border-2 border-dashed border-gray-400 bg-gray-50">
                    <h3 class="text-gray-500 font-bold text-lg">DATABASE KOSONG</h3>
                    <p class="mb-4 text-gray-400">Belum ada unit bisnis yang terdaftar dalam sistem.</p>
                    
                    <a href="{{ route('owner.toko.create') }}" 
                       class="inline-block bg-blue-700 text-white px-4 py-2 font-bold hover:bg-blue-800 border-2 border-blue-900">
                        BUAT DATA BARU
                    </a>
                </div>
            @endforelse
        </div>
    </div>
@endsection