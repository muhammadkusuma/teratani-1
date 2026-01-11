<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - TERATANI ERP</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            background-color: #f0f2f5;
        }

        * {
            border-radius: 0 !important;
        }

        /* Gaya Khusus Navigasi */
        .nav-row {
            display: flex;
            flex-wrap: wrap;
            background-color: #1e3a8a;
            border-bottom: 1px solid #1e40af;
            padding: 1px 0;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 4px 10px;
            color: white;
            text-decoration: none;
            border-right: 1px solid #3b82f6;
            font-weight: 500;
            transition: background 0.2s;
            white-space: nowrap;
            cursor: pointer;
        }

        .nav-link:hover {
            background-color: #f97316;
        }

        .nav-link.active {
            background-color: #0369a1;
            font-weight: bold;
        }

        .nav-link i {
            font-size: 12px;
            margin-right: 5px;
        }

        /* Scrollbar Klasik */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #e2e8f0;
        }

        ::-webkit-scrollbar-thumb {
            background: #94a3b8;
            border: 2px solid #e2e8f0;
        }
    </style>
</head>

<body class="flex flex-col min-h-screen">

    <header class="bg-gradient-to-r from-teal-500 to-cyan-600 text-white border-b-4 border-yellow-500">
        <div class="flex justify-between items-center px-4 py-2">
            <div class="flex items-center gap-4">
                <div>
                    <h1 class="text-2xl font-black italic tracking-tighter leading-none">TERATANI SYSTEM</h1>
                    <p class="text-[10px] font-bold tracking-[0.2em] uppercase text-teal-100">
                        Tanam Kebaikan • Tuai Kemudahan
                    </p>
                </div>
            </div>
            <div class="hidden md:block bg-black/20 p-2 border border-white/20">
                <table class="text-[10px]">
                    <tr>
                        <td class="pr-2 opacity-80">PENGGUNA:</td>
                        <td class="font-bold text-yellow-300">{{ strtoupper(Auth::user()->name ?? 'User') }}</td>
                    </tr>
                    <tr>
                        <td class="pr-2 opacity-80">ROLE:</td>
                        <td class="font-bold">OWNER MANAGEMENT</td>
                    </tr>
                </table>
            </div>
        </div>
    </header>

    <nav class="shadow-md select-none">
        <div class="nav-row bg-blue-950">
            <a href="{{ route('owner.dashboard') }}"
                class="nav-link {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i> Home
            </a>

            @if (session('toko_active_id'))
                <a href="{{ route('owner.kasir.index') }}"
                    class="nav-link {{ request()->routeIs('owner.kasir.*') ? 'active' : '' }}">
                    <i class="fas fa-cash-register"></i> Kasir (POS)
                </a>
                <a href="{{ route('owner.toko.produk.index', session('toko_active_id')) }}"
                    class="nav-link {{ request()->routeIs('owner.toko.produk.*') ? 'active' : '' }}">
                    <i class="fas fa-boxes"></i> Produk
                </a>

                <a href="{{ route('owner.kategori.index') }}"
                    class="nav-link {{ request()->routeIs('owner.kategori.*') ? 'active' : '' }}">
                    <i class="fas fa-tags"></i> Kategori
                </a>
                <a href="{{ route('owner.satuan.index') }}"
                    class="nav-link {{ request()->routeIs('owner.satuan.*') ? 'active' : '' }}">
                    <i class="fas fa-weight-hanging"></i> Satuan
                </a>
                <a href="{{ route('owner.mutasi.index') }}"
                    class="nav-link {{ request()->routeIs('owner.mutasi.*') ? 'active' : '' }}">
                    <i class="fas fa-exchange-alt"></i> Mutasi
                </a>
                <a href="{{ route('owner.pelanggan.index') }}"
                    class="nav-link {{ request()->routeIs('owner.pelanggan.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Pelanggan
                </a>
                <a href="{{ route('owner.pengeluaran.index') }}"
                    class="nav-link {{ request()->routeIs('owner.pengeluaran.*') ? 'active' : '' }}">
                    <i class="fas fa-file-invoice-dollar"></i> Pengeluaran
                </a>

                <a href="{{ route('owner.laporan.keuangan') }}"
                    class="nav-link {{ request()->routeIs('owner.laporan.keuangan') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i> Laporan Keuangan
                </a>
            @endif

            <form action="{{ route('logout') }}" method="POST" class="ml-auto flex">
                @csrf
                <button type="submit"
                    class="nav-link bg-red-700 hover:!bg-red-600 border-l border-white/20 text-white">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </nav>

    <div
        class="bg-cyan-50 border-b border-cyan-200 p-1 px-3 flex items-center text-[10px] text-cyan-800 font-semibold shadow-inner">
        <div class="flex items-center gap-1 mr-4">
            <i class="fas fa-map-marker-alt text-cyan-500"></i>
            <span>Lokasi: Owner Area &gt; @yield('title')</span>
        </div>

        @if (session('toko_active_nama'))
            <div class="ml-auto flex items-center gap-2 border-l border-cyan-300 pl-3">
                <div>
                    <i class="fas fa-building text-cyan-500"></i>
                    UNIT AKTIF: <span class="text-blue-700 uppercase">{{ session('toko_active_nama') }}</span>
                </div>
                {{-- Tombol Ganti Toko --}}
                <a href="{{ route('owner.toko.index') }}"
                    class="bg-yellow-400 hover:bg-yellow-300 text-yellow-900 px-2 py-0.5 rounded text-[9px] font-bold border border-yellow-600 shadow-sm transition"
                    title="Ganti Unit Bisnis / Cabang">
                    <i class="fas fa-exchange-alt"></i> Ganti
                </a>
            </div>
        @else
            <div class="ml-auto text-red-500 font-bold border-l border-red-300 pl-3">
                <i class="fas fa-exclamation-circle"></i> BELUM PILIH TOKO
            </div>
        @endif
    </div>

    <main class="flex-1 p-1 bg-slate-200 overflow-hidden flex flex-col">
        <div class="bg-white border border-slate-400 shadow-sm flex-1 overflow-auto">
            @yield('content')
        </div>
    </main>

    <footer class="bg-slate-800 text-slate-300 text-[9px] px-3 py-1 flex justify-between border-t border-black">
        <div class="flex gap-4">
            <span>Server Time: <b>{{ date('H:i:s') }}</b></span>
            <span>Database: <b>MariaDB 10.4</b></span>
            <span>Status: <b class="text-green-400">Connected</b></span>
        </div>
        <div class="font-bold">
            &copy; {{ date('Y') }} PT. TERATANI SISTEM ERP • v2.0.26
        </div>
    </footer>

</body>

</html>
