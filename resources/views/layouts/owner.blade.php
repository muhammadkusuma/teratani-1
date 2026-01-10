<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Teratani Owner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-800" x-data="{ sidebarOpen: false }">

    <div x-show="sidebarOpen" x-transition:enter="transition opacity-0 ease-linear duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition opacity-0 ease-linear duration-300" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black/50 md:hidden"
        x-cloak>
    </div>

    <div class="flex items-center justify-between bg-white border-b px-4 py-3 md:hidden sticky top-0 z-30">
        <div class="flex items-center gap-2">
            <div class="bg-green-600 p-1.5 rounded text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <span class="font-bold text-lg text-gray-900">Teratani</span>
        </div>
        <button @click="sidebarOpen = !sidebarOpen"
            class="text-gray-600 focus:outline-none p-2 hover:bg-gray-100 rounded-lg">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path x-show="!sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16" />
                <path x-show="sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M6 18L18 6M6 6l12 12" x-cloak />
            </svg>
        </button>
    </div>

    <div class="flex min-h-screen relative">
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transform transition-transform duration-300 md:translate-x-0 md:static md:h-screen md:sticky md:top-0 overflow-y-auto">

            <div class="h-16 flex items-center px-6 border-b border-gray-100 hidden md:flex">
                <div class="bg-green-600 p-1.5 rounded mr-3 text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <span class="text-xl font-bold text-gray-900">Teratani</span>
            </div>

            <div class="p-4 pb-24">
                <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 px-2">Menu Utama</div>
                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="#">Teratani Owner</a>

                        <div class="d-flex align-items-center">
                            @if (session('toko_active_nama'))
                                <span class="me-2 badge bg-success">
                                    Toko Aktif: {{ session('toko_active_nama') }}
                                </span>
                                <a href="{{ route('owner.toko.index') }}" class="btn btn-sm btn-outline-secondary">
                                    Ganti Toko
                                </a>
                            @endif
                        </div>
                    </div>
                </nav>
            </div>

            <div class="fixed md:absolute bottom-0 w-64 border-t border-gray-200 p-4 bg-white z-50">
                <div class="flex items-center gap-3">
                    <div
                        class="w-9 h-9 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-bold text-sm shrink-0">
                        {{ substr(Auth::user()->nama_lengkap ?? 'User', 0, 2) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->nama_lengkap }}</p>
                        <p class="text-xs text-gray-500 truncate">Owner</p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="shrink-0">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-red-500 p-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <main class="flex-1 w-full max-w-full min-w-0 bg-gray-50 p-4 md:p-8 pb-20 md:pb-8">
            @yield('content')
        </main>
    </div>
</body>

</html>
