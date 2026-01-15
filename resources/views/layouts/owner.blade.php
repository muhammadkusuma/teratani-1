<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Sistem Toko Tani</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Tahoma', 'MS Sans Serif', Arial, sans-serif;
            font-size: 16px;
        }

        .win98-border {
            border: 3px solid;
            border-color: #ffffff #000000 #000000 #ffffff;
            box-shadow: 1px 1px 0 #808080;
        }

        .win98-inset {
            border: 2px solid;
            border-color: #000000 #ffffff #ffffff #000000;
        }

        .win98-button {
            border: 3px solid;
            border-color: #ffffff #000000 #000000 #ffffff;
            background: #c0c0c0;
            padding: 8px 16px;
            font-weight: bold;
            cursor: pointer;
        }

        .win98-button:hover {
            background: #d4d0c8;
        }

        .win98-button:active {
            border-color: #000000 #ffffff #ffffff #000000;
        }

        .menu-item {
            padding: 4px 12px;
            font-size: 13px;
            font-weight: bold;
            border: 2px solid transparent;
        }

        .menu-item:hover {
            background: #000080;
            color: white;
        }

        .menu-item.active {
            border: 2px solid;
            border-color: #000000 #ffffff #ffffff #000000;
            background: #c0c0c0;
        }

        /* Global Helper Classes */
        .win98-panel {
            background: white;
            border: 2px solid;
            border-color: #000000 #ffffff #ffffff #000000;
            padding: 16px;
        }

        .win98-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 15px;
        }

        .win98-table th {
            background: #000080;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            font-size: 16px;
            border: 1px solid #000;
        }

        .win98-table td {
            background: white;
            padding: 10px;
            border: 1px solid #808080;
            font-size: 15px;
        }

        .win98-table tr:nth-child(even) td {
            background: #f0f0f0;
        }

        .win98-input {
            font-family: 'Tahoma', Arial, sans-serif;
            font-size: 16px;
            padding: 6px;
            border: 2px solid;
            border-color: #000000 #ffffff #ffffff #000000;
            background: white;
        }

        .win98-input:focus {
            outline: 2px dotted #000;
        }

        .win98-heading {
            font-size: 20px;
            color: #000080;
            font-weight: bold;
            margin-bottom: 12px;
        }

        .win98-text-large {
            font-size: 16px;
        }
    </style>
</head>

<body class="bg-teal-700 overflow-hidden h-screen">
    <div class="win98-border bg-gray-300 h-screen flex flex-col">
        <!-- Title Bar -->
        <div class="bg-gradient-to-r from-blue-900 to-blue-600 text-white px-2 py-1.5 flex justify-between items-center">
            <span class="font-bold text-base">📊 SISTEM TOKO TANI - {{ session('toko_active_nama', 'Pilih Toko') }}</span>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="win98-button text-sm px-3 py-1">✕ Keluar</button>
            </form>
        </div>

        <!-- Menu Bar -->
        <div class="bg-gray-300 border-b-2 border-gray-400 p-1 flex flex-wrap gap-1">
            <a href="{{ route('owner.dashboard') }}" 
               class="menu-item {{ request()->routeIs('owner.dashboard') ? 'active' : '' }} text-black no-underline">
                🏠 Beranda
            </a>

            @if (session('toko_active_id'))
                <a href="{{ route('owner.kasir.index') }}" 
                   class="menu-item {{ request()->routeIs('owner.kasir.*') ? 'active' : '' }} text-black no-underline">
                    💰 Kasir
                </a>
                <a href="{{ route('owner.toko.produk.index', session('toko_active_id')) }}" 
                   class="menu-item {{ request()->routeIs('owner.toko.produk.*') ? 'active' : '' }} text-black no-underline">
                    📦 Produk
                </a>
                <a href="{{ route('owner.stok.index') }}" 
                   class="menu-item {{ request()->routeIs('owner.stok.*') ? 'active' : '' }} text-black no-underline">
                    📊 Stok
                </a>
                <a href="{{ route('owner.pelanggan.index') }}" 
                   class="menu-item {{ request()->routeIs('owner.pelanggan.*') ? 'active' : '' }} text-black no-underline">
                    👥 Pelanggan
                </a>
                <a href="{{ route('owner.distributor.index') }}" 
                   class="menu-item {{ request()->routeIs('owner.distributor.*') ? 'active' : '' }} text-black no-underline">
                    🚚 Distributor
                </a>
                <a href="{{ route('owner.karyawan.index') }}" 
                   class="menu-item {{ request()->routeIs('owner.karyawan.*') ? 'active' : '' }} text-black no-underline">
                    👨‍💼 Karyawan
                </a>
                <a href="{{ route('owner.pengeluaran.index') }}" 
                   class="menu-item {{ request()->routeIs('owner.pengeluaran.*') ? 'active' : '' }} text-black no-underline">
                    💸 Pengeluaran
                </a>
                <a href="{{ route('owner.pendapatan_pasif.index') }}" 
                   class="menu-item {{ request()->routeIs('owner.pendapatan_pasif.*') ? 'active' : '' }} text-black no-underline">
                    💰 Pendapatan
                </a>
            @endif

            <a href="{{ route('owner.perusahaan.index') }}" 
               class="menu-item {{ request()->routeIs('owner.perusahaan.*') ? 'active' : '' }} text-black no-underline">
                🏢 Detail Perusahaan
            </a>

            <a href="{{ route('owner.toko.index') }}" 
               class="menu-item {{ request()->routeIs('owner.toko.index') ? 'active' : '' }} text-black no-underline">
                🏪 Toko
            </a>

            <a href="{{ route('owner.profile.edit-password') }}" 
               class="menu-item {{ request()->routeIs('owner.profile.*') ? 'active' : '' }} text-black no-underline">
                🔐 Ubah Password
            </a>
        </div>

        <!-- Content Area -->
        <div class="flex-1 overflow-y-auto p-3 bg-gray-300">
            @if (session('success'))
                <div class="bg-green-400 border-4 border-green-600 p-4 mb-3 font-bold text-base text-black">
                    ✓ {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-500 border-4 border-red-800 p-4 mb-3 font-bold text-base text-white">
                    ✗ {{ session('error') }}
                </div>
            @endif

            @if (session('warning'))
                <div class="bg-yellow-300 border-4 border-yellow-600 p-4 mb-3 font-bold text-base text-black">
                    ⚠ {{ session('warning') }}
                </div>
            @endif

            @yield('content')
        </div>

        <!-- Status Bar -->
        <div class="bg-gray-300 border-t-2 border-gray-400 px-3 py-1.5 flex justify-between text-base">
            <span class="font-bold">👤 {{ Auth::user()->nama_lengkap ?? Auth::user()->username }}</span>
            <span class="font-bold">🕐 <span id="clock"></span></span>
        </div>
    </div>

    <script>
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('clock').textContent = `${hours}:${minutes}:${seconds}`;
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>

    @stack('scripts')
</body>
</html>
