@extends('layouts.owner')

@section('content')
    <div class="space-y-6">

        <!-- Header -->
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Toko</h1>
            <p class="text-sm text-gray-500 mt-1">Daftar Toko Cabang</p>
        </div>

        <!-- Alert Success -->
        @if (session('success'))
            <div class="flex items-start gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl">
                <svg class="w-5 h-5 mt-0.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Alert Error -->
        @if (session('error'))
            <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl">
                <svg class="w-5 h-5 mt-0.5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">

            <!-- Card Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 px-6 py-4 border-b">
                <div class="flex items-center gap-2 font-semibold text-gray-800">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 21h18M3 7l9-4 9 4M4 10h16v11H4z" />
                    </svg>
                    Data Toko
                </div>

                <a href="{{ route('owner.toko.create') }}"
                    class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl text-sm font-semibold shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Toko
                </a>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Kode Toko</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nama Toko</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Kota</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">No Telp</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y">
                        @forelse($toko as $index => $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-5 py-4 text-gray-600">{{ $index + 1 }}</td>
                                <td class="px-5 py-4 font-mono text-gray-700">{{ $item->kode_toko }}</td>
                                <td class="px-5 py-4 font-semibold text-gray-900">
                                    {{ $item->nama_toko }}
                                    @if ($item->is_pusat)
                                        <span
                                            class="ml-2 px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">
                                            Pusat
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-gray-700">{{ $item->kota }}</td>
                                <td class="px-5 py-4 text-gray-700">{{ $item->no_telp }}</td>
                                <td class="px-5 py-4">
                                    @if ($item->is_active)
                                        <span
                                            class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                            Aktif
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-gray-200 text-gray-700">
                                            Non-Aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <div class="flex justify-center gap-2 flex-wrap">

                                        <!-- Masuk -->
                                        <a href="{{ route('owner.toko.select', $item->id_toko) }}"
                                            class="inline-flex items-center gap-1 bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4m-4-4l4-4m0 0l-4-4m4 4H3" />
                                            </svg>
                                            Masuk
                                        </a>

                                        <!-- Edit -->
                                        <a href="{{ route('owner.toko.edit', $item->id_toko) }}"
                                            class="inline-flex items-center gap-1 bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5h2m-1-1v2m-7 4l2-2m0 0l7-7 7 7M5 13h14" />
                                            </svg>
                                            Edit
                                        </a>

                                        <!-- Hapus -->
                                        @if (!$item->is_pusat)
                                            <form action="{{ route('owner.toko.destroy', $item->id_toko) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus toko ini? Data terkait mungkin akan hilang.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1 bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                    Belum ada data toko. Silakan tambah toko baru.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
