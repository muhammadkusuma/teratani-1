<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Teratani Owner</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Alpine -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-800" x-data="{ sidebarOpen: false }">

    <!-- Overlay Mobile -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity
        class="fixed inset-0 bg-black/50 z-40 md:hidden" x-cloak>
    </div>

    <!-- Mobile Header -->
    <header class="md:hidden sticky top-0 z-30 bg-white border-b">
        <div class="flex items-center justify-between px-4 py-3">
            <div class="flex items-center gap-2">
                <div class="bg-green-600 p-2 rounded-lg text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <span class="font-semibold text-lg">Teratani</span>
            </div>

            <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg hover:bg-gray-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                    <path x-show="sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" x-cloak />
                </svg>
            </button>
        </div>
    </header>

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed md:static inset-y-0 left-0 z-50 w-64 bg-white border-r transform transition-transform duration-300 md:translate-x-0">

            <!-- Logo -->
            <div class="h-16 flex items-center px-6 border-b">
                <div class="bg-green-600 p-2 rounded-lg text-white mr-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <span class="text-xl font-bold">Teratani</span>
            </div>

            <!-- Sidebar Content -->
            <div class="p-4 space-y-6 pb-28 overflow-y-auto">

                <!-- Toko Aktif -->
                @if (session('toko_active_nama'))
                    <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                        <p class="text-xs text-green-700 font-semibold mb-1">TOKO AKTIF</p>
                        <p class="font-semibold text-green-900">
                            {{ session('toko_active_nama') }}
                        </p>
                        <a href="{{ route('owner.toko.index') }}"
                            class="inline-block mt-3 text-sm text-green-700 hover:underline">
                            Ganti Toko →
                        </a>
                    </div>
                @endif

                <!-- Menu -->
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 px-2">
                        Menu Utama
                    </p>

                    <nav class="space-y-1">
                        <a href="{{ route('owner.dashboard') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('owner.dashboard') ? 'bg-gray-100 text-blue-600 font-medium' : 'hover:bg-gray-100 text-gray-700' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('owner.dashboard') ? 'text-blue-600' : 'text-gray-400' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Dashboard
                        </a>

                        @if (session('toko_active_id'))
                            <a href="{{ route('owner.toko.produk.index', session('toko_active_id')) }}"
                                class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('owner.toko.produk.*') ? 'bg-gray-100 text-blue-600 font-medium' : 'hover:bg-gray-100 text-gray-700' }}">
                                <svg class="w-5 h-5 {{ request()->routeIs('owner.toko.produk.*') ? 'text-blue-600' : 'text-gray-400' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                Produk / Stok
                            </a>
                        @endif
                    </nav>
                </div>
            </div>

            <!-- User Footer -->
            <div class="absolute bottom-0 w-full border-t bg-white p-4">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-full bg-green-100 text-green-700 flex items-center justify-center font-bold">
                        {{ substr(Auth::user()->nama_lengkap ?? 'User', 0, 2) }}
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold truncate">
                            {{ Auth::user()->nama_lengkap }}
                        </p>
                        <p class="text-xs text-gray-500">Owner</p>
                    </div>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="p-2 rounded-lg hover:bg-red-50 text-gray-400 hover:text-red-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-4 md:p-8 bg-gray-100">
            @yield('content')
        </main>

    </div>

</body>

</html>
