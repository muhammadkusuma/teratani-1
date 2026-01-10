<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') TERATANI</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">


    <style>
        /* Paksa Font Klasik */
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            background-color: #e0e0e0;
            /* Background abu-abu windows lama */
        }

        /* Reset semua rounded default tailwind jika ada yang terlewat */
        * {
            border-radius: 0 !important;
        }

        /* Scrollbar gaya lama (Chrome/Webkit) */
        ::-webkit-scrollbar {
            width: 12px;
            height: 12px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border: 2px solid #f1f1f1;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>

<body class="flex flex-col min-h-screen">

    <header
        class="bg-teal-700 text-white border-b-4 border-yellow-500 px-3 py-2 flex justify-between items-center shadow-md">
        <div class="flex items-center gap-3">
            <div class="bg-white p-1 border border-gray-600">
                <svg class="w-6 h-6 text-teal-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <div>
                <h1 class="font-bold text-lg leading-none tracking-wider">SISTEM INFORMASI TERATANI</h1>
                <p class="text-[10px] text-teal-100 opacity-80">Enterprise Resource Planning v.2.0</p>
            </div>
        </div>

        <div class="text-right hidden md:block">
            <table class="text-[10px] text-white">
                <tr>
                    <td class="text-right pr-2">Pengguna :</td>
                    <td class="font-bold bg-teal-800 px-1 border border-teal-600">{{ Auth::user()->name ?? 'User' }}
                    </td>
                </tr>
                <tr>
                    <td class="text-right pr-2">Akses :</td>
                    <td class="font-bold text-yellow-300">OWNER</td>
                </tr>
            </table>
        </div>
    </header>

    <nav class="bg-blue-900 text-white border-b border-black shadow-sm">
        <div class="flex flex-wrap items-center">

            <a href="{{ route('owner.dashboard') }}"
                class="px-4 py-2 hover:bg-orange-500 border-r border-blue-700 flex items-center gap-1 transition-colors {{ request()->routeIs('owner.dashboard') ? 'bg-orange-600 font-bold' : '' }}">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                DASHBOARD
            </a>

            @if (session('toko_active_id'))
                <a href="{{ route('owner.toko.produk.index', session('toko_active_id')) }}"
                    class="px-4 py-2 hover:bg-orange-500 border-r border-blue-700 flex items-center gap-1 transition-colors {{ request()->routeIs('owner.toko.produk.*') ? 'bg-orange-600 font-bold' : '' }}">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    DATA BARANG
                </a>

                <a href="{{ route('owner.mutasi.index') }}"
                    class="px-4 py-2 hover:bg-orange-500 border-r border-blue-700 flex items-center gap-1 transition-colors {{ request()->routeIs('owner.mutasi.*') ? 'bg-orange-600 font-bold' : '' }}">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                    MUTASI STOK
                </a>

                <a href="#"
                    class="px-4 py-2 hover:bg-orange-500 border-r border-blue-700 flex items-center gap-1 transition-colors">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    LAPORAN
                </a>

                <a href="#"
                    class="px-4 py-2 hover:bg-orange-500 border-r border-blue-700 flex items-center gap-1 transition-colors">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    UTILITAS
                </a>
            @endif

            <div class="flex-grow"></div>

            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit"
                    class="px-4 py-2 bg-red-800 hover:bg-red-600 border-l border-red-900 text-white font-bold flex items-center gap-1 text-[10px]">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    KELUAR (LOGOUT)
                </button>
            </form>
        </div>
    </nav>

    <div class="bg-gray-200 border-b border-gray-400 p-1 px-3 flex items-center gap-2 text-gray-600 shadow-inner">
        <span class="font-bold text-gray-800">Posisi:</span>
        <span>Owner Area</span>
        <span>&gt;</span>
        <span class="text-blue-700 font-bold">@yield('title')</span>

        @if (session('toko_active_nama'))
            <span class="ml-auto border-l border-gray-400 pl-3 flex items-center gap-1">
                Unit Bisnis: <b class="text-green-700">{{ session('toko_active_nama') }}</b>
            </span>
        @endif
    </div>

    <main class="flex-1 p-2 bg-[#f0f0f0]">
        <div class="bg-white border border-gray-400 p-3 min-h-[400px]">
            @yield('content')
        </div>
    </main>

    <footer
        class="bg-gray-300 border-t border-gray-500 p-1 px-3 text-[10px] text-gray-700 flex justify-between select-none">
        <div class="flex gap-4">
            <span>Waktu: <b>{{ date('H:i:s') }}</b></span>
        </div>
        <div>
            &copy; {{ date('Y') }} PT. TERATANI SISTEM. Hak Cipta Dilindungi.
        </div>
    </footer>

</body>

</html>
