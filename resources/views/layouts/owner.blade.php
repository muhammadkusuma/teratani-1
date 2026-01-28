<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Sistem Toko Tani</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Select2 Windows 98 Theme Fixes */
        /* Prevent FOUC: Style native select to match Select2 dimensions */
        select {
            height: 30px;
            padding: 4px 6px; /* Approx p-1.5 */
            border: 1px solid #9ca3af; /* border-gray-400 */
            font-family: 'Tahoma', sans-serif;
            font-size: 12px; /* text-xs */
            width: 100%;
            box-sizing: border-box;
            background: #fff;
        }
        
        .select2-container .select2-selection--single {
            height: 30px !important; /* Match text-xs + p-1.5 input height */
            border: 1px solid #9ca3af !important; /* Match border-gray-400 */
            border-radius: 0 !important;
            background-color: #ffffff !important;
            display: flex !important;
            align-items: center !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 28px !important; /* 30px - 2px border */
            width: 20px !important;
            background: #e5e7eb; /* gray-200 */
            border-left: 1px solid #9ca3af;
            top: 0px !important;
            right: 0px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 28px !important;
            padding-left: 6px !important;
            font-family: 'Tahoma', sans-serif;
            font-size: 12px !important; /* text-xs */
            color: #000;
        }
        .select2-dropdown {
            border: 1px solid #9ca3af !important;
            border-radius: 0 !important;
            font-size: 12px !important;
        }
        .select2-results__option {
            padding: 6px;
        }
    </style>
    
    <style>
        body {
            font-family: 'Tahoma', 'MS Sans Serif', Arial, sans-serif;
            font-size: 18px;
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
            padding: 10px 18px;
            font-weight: bold;
            cursor: pointer;
            font-size: 16px;
        }

        .win98-button:hover {
            background: #d4d0c8;
        }

        .win98-button:active {
            border-color: #000000 #ffffff #ffffff #000000;
        }

        .menu-item {
            padding: 4px 10px;
            font-size: 13px;
            font-weight: bold;
            border: 2px solid transparent;
            cursor: pointer;
            position: relative;
        }

        .menu-item:hover, .menu-item.active {
            background: #000080;
            color: white;
        }

        /* Dropdown Styles */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #c0c0c0;
            min-width: 160px;
            box-shadow: 2px 2px 0px black;
            border: 2px solid;
            border-color: #ffffff #000000 #000000 #ffffff;
            z-index: 100;
            top: 100%;
            left: 0;
        }

        .dropdown-content a {
            color: black;
            padding: 8px 12px;
            text-decoration: none;
            display: block;
            font-size: 13px;
            font-weight: normal;
        }

        .dropdown-content a:hover {
            background-color: #000080;
            color: white;
        }

        .dropdown:hover .dropdown-content {
            /* display: block;  <-- REMOVED: Managed by JS now */
        }

        .dropdown-content.show {
            display: block;
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
            font-size: 17px;
        }

        .win98-table th {
            background: #000080;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: bold;
            font-size: 18px;
            border: 1px solid #000;
        }

        .win98-table td {
            background: white;
            padding: 12px;
            border: 1px solid #808080;
            font-size: 17px;
        }

        .win98-table tr:nth-child(even) td {
            background: #f0f0f0;
        }

        .win98-input {
            font-family: 'Tahoma', Arial, sans-serif;
            font-size: 18px;
            padding: 8px;
            border: 2px solid;
            border-color: #000000 #ffffff #ffffff #000000;
            background: white;
        }

        .win98-input:focus {
            outline: 2px dotted #000;
        }

        .win98-heading {
            font-size: 24px;
            color: #000080;
            font-weight: bold;
            margin-bottom: 12px;
        }

        .win98-text-large {
            font-size: 18px;
        }
    </style>
</head>

<body class="bg-teal-700 overflow-hidden h-screen">
    <div class="win98-border bg-gray-300 h-screen flex flex-col">
        
        {{-- Header Bar --}}
        <div class="bg-gradient-to-r from-blue-900 to-blue-600 text-white px-2 md:px-3 py-1.5 md:py-2 flex justify-between items-center gap-2">
            <span class="font-bold text-xs md:text-sm lg:text-base truncate flex-1 min-w-0">
                <span class="hidden md:inline">📊 SISTEM TOKO TANI - </span>
                <span class="md:hidden">🏪 </span>{{ session('toko_active_nama', 'Pilih Toko') }}
            </span>
            <form action="{{ route('logout') }}" method="POST" class="inline flex-shrink-0">
                @csrf
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold border-2 border-red-800 shadow-md text-xs md:text-sm px-2 md:px-3 py-1 md:py-1.5 whitespace-nowrap transition-colors rounded-sm">
                    <span class="md:hidden">✕</span>
                    <span class="hidden md:inline">✕ Keluar</span>
                </button>
            </form>
        </div>

        
        {{-- Navigation Bar --}}
        <div class="bg-gray-300 border-b-2 border-gray-400 p-1 md:p-1.5 flex flex-wrap gap-1 relative items-center">
            
            {{-- Mobile Menu Button --}}
            <button id="mobile-menu-btn" class="md:hidden win98-button flex items-center gap-1 md:gap-2 text-xs md:text-sm px-2 py-1">
                <i class="fa-solid fa-bars"></i> <span class="hidden sm:inline">Menu</span>
            </button>

            {{-- Navigation Menu --}}
            <div id="nav-menu" class="hidden md:flex flex-wrap gap-1 w-full md:w-auto absolute md:relative top-full left-0 md:top-auto md:left-auto z-50 bg-gray-300 md:bg-transparent p-2 md:p-0 win98-border md:border-none shadow-lg md:shadow-none flex-col md:flex-row max-h-[80vh] md:max-h-none overflow-y-auto md:overflow-visible">
                
                @php
                    $jabatan = Auth::user()->jabatan;

                    // Definisi Level Akses
                    // 'Owner' etc have full access
                    $level_full   = ['Owner', 'Manager', 'Supervisor', 'Admin', 'Unknown']; 
                    
                    // Specific Access Arrays (Strict Mode)
                    // Users with these roles ONLY see their specific menus
                    $is_kasir_strict  = in_array($jabatan, ['Kasir', 'Sales']);
                    $is_gudang_strict = in_array($jabatan, ['Staff Gudang']);
                    $is_full_access   = in_array($jabatan, $level_full);

                    // Backward compatibility helper arrays (can be removed if we fully switch logic below)
                    $level_kasir  = array_merge($level_full, ['Kasir', 'Sales']); 
                    $level_gudang = array_merge($level_full, ['Staff Gudang']);
                @endphp

                @if($is_full_access)
                <a href="{{ route('owner.dashboard') }}" 
                class="menu-item {{ request()->routeIs('owner.dashboard') ? 'active' : '' }} text-black no-underline block md:inline-block">
                    🏠 Beranda
                </a>
                @endif

                {{-- Organisasi Dropdown (Global Admin Items) --}}
                @if(in_array($jabatan, $level_full))
                    <div class="dropdown inline-block" onclick="toggleDropdown(this)">
                        <div class="menu-item {{ request()->routeIs('owner.users.*') || request()->routeIs('owner.karyawan.*') || request()->routeIs('owner.perusahaan.*') || request()->routeIs('owner.toko.index') ? 'active' : '' }} text-black">
                            🏢 Organisasi ▼
                        </div>
                        <div class="dropdown-content">
                            <a href="{{ route('owner.users.index') }}">👥 Akun Pengguna</a>
                            @if (session('toko_active_id'))
                                <a href="{{ route('owner.karyawan.index') }}">👨‍💼 Karyawan</a>
                            @endif
                            <a href="{{ route('owner.perusahaan.index') }}">🏢 Detail Perusahaan</a>
                            <a href="{{ route('owner.toko.index') }}">🏪 Toko</a>
                        </div>
                    </div>
                @endif

                @if (session('toko_active_id'))
                    @if(in_array($jabatan, $level_kasir))
                        <a href="{{ route('owner.kasir.index') }}" 
                        class="menu-item {{ request()->routeIs('owner.kasir.*') ? 'active' : '' }} text-black no-underline block md:inline-block">
                            💰 Kasir
                        </a>
                    @endif

                    @if(in_array($jabatan, $level_gudang))
                        {{-- Inventaris Dropdown --}}
                        <div class="dropdown inline-block" onclick="toggleDropdown(this)">
                            <div class="menu-item {{ request()->routeIs('owner.toko.produk.*') || request()->routeIs('owner.stok.*') || request()->routeIs('owner.toko.gudang.*') || request()->routeIs('owner.riwayat-stok.*') ? 'active' : '' }} text-black">
                                📦 Inventaris ▼
                            </div>
                            <div class="dropdown-content">
                                <a href="{{ route('owner.toko.produk.index', session('toko_active_id')) }}">📦 Produk</a>
                                <a href="{{ route('owner.stok.index') }}">📊 Stok</a>
                                <a href="{{ route('owner.toko.gudang.index', session('toko_active_id')) }}">🏭 Gudang</a>
                                <a href="{{ route('owner.riwayat-stok.index') }}">📋 Log Stok</a>
                            </div>
                        </div>
                    @endif

                    @if(in_array($jabatan, $level_kasir))
                        <div class="dropdown inline-block" onclick="toggleDropdown(this)">
                            <div class="menu-item {{ request()->routeIs('owner.pelanggan.*') || request()->routeIs('owner.retur-penjualan.*') ? 'active' : '' }} text-black">
                                👥 Pelanggan ▼
                            </div>
                            <div class="dropdown-content">
                                <a href="{{ route('owner.retur-penjualan.index') }}">🔄 Retur Penjualan</a>
                                <a href="{{ route('owner.pelanggan.index') }}">👥 Data Pelanggan</a>
                                <a href="{{ route('owner.pelanggan.piutang.index') }}">💰 Kelola Piutang</a>
                            </div>
                        </div>
                    @endif

                    @if(in_array($jabatan, $level_gudang))
                        <div class="dropdown inline-block" onclick="toggleDropdown(this)">
                            <div class="menu-item {{ request()->routeIs('owner.distributor.*') || request()->routeIs('owner.retur-pembelian.*') || request()->routeIs('owner.toko.pembelian.*') ? 'active' : '' }} text-black">
                                🚚 Distributor ▼
                            </div>
                            <div class="dropdown-content">
                                <a href="{{ route('owner.toko.pembelian.index', session('toko_active_id')) }}">🧺 Pembelian</a>
                                <a href="{{ route('owner.retur-pembelian.index') }}">🔙 Retur Distributor</a>
                                <a href="{{ route('owner.distributor.index') }}">🚚 Data Distributor</a>
                                <a href="{{ route('owner.distributor.hutang.index') }}">💰 Kelola Hutang</a>
                            </div>
                        </div>
                    @endif

                    @if($is_full_access)
                         {{-- Keuangan Dropdown --}}
                         <div class="dropdown inline-block" onclick="toggleDropdown(this)">
                            <div class="menu-item {{ request()->routeIs('owner.pengeluaran.*') || request()->routeIs('owner.pendapatan_pasif.*') ? 'active' : '' }} text-black">
                                💵 Keuangan ▼
                            </div>
                            <div class="dropdown-content">
                                <a href="{{ route('owner.pengeluaran.index') }}">💸 Pengeluaran</a>
                                @if(in_array($jabatan, $level_full))
                                    <a href="{{ route('owner.pendapatan_pasif.index') }}">💰 Pendapatan</a>
                                @endif
                            </div>
                        </div>
                    @endif
                @endif

                {{-- Ganti Toko Dropdown --}}
                @php
                    $tokos = Auth::user()->perusahaan->tokos ?? [];
                @endphp
                @if(count($tokos) > 0 && $is_full_access)
                    <div class="dropdown inline-block" onclick="toggleDropdown(this)">
                        <div class="menu-item text-black">
                            🏪 Ganti Toko ▼
                        </div>
                        <div class="dropdown-content">
                            @foreach($tokos as $toko)
                                <a href="{{ route('owner.toko.select', $toko->id_toko) }}" 
                                   class="{{ session('toko_active_id') == $toko->id_toko ? 'font-bold bg-blue-100' : '' }}">
                                   {{ $toko->nama_toko }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <a href="{{ route('owner.profile.index') }}" 
                class="menu-item {{ request()->routeIs('owner.profile.*') ? 'active' : '' }} text-black no-underline block md:inline-block">
                    👤 Profil Saya
                </a>
            </div>
        </div>

        
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

        // Hamburger Menu Toggle
        const menuBtn = document.getElementById('mobile-menu-btn');
        const navMenu = document.getElementById('nav-menu');

        menuBtn.addEventListener('click', () => {
            navMenu.classList.toggle('hidden');
            navMenu.classList.toggle('flex');
        });

        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!menuBtn.contains(e.target) && !navMenu.contains(e.target)) {
                if (!navMenu.classList.contains('hidden') && window.innerWidth < 768) {
                    navMenu.classList.add('hidden');
                    navMenu.classList.remove('flex');
                }
            }
            
            // Close dropdowns when clicking outside
            if (!e.target.closest('.dropdown')) {
                document.querySelectorAll('.dropdown-content').forEach(el => {
                    el.classList.remove('show');
                });
            }
        });

        // Toggle dropdown function
        function toggleDropdown(element) {
            // Close other dropdowns first
            document.querySelectorAll('.dropdown-content').forEach(el => {
                if (el !== element.querySelector('.dropdown-content')) {
                    el.classList.remove('show');
                }
            });
            
            // Toggle current
            const content = element.querySelector('.dropdown-content');
            content.classList.toggle('show');
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('select:not(.manual-select2)').select2({
                width: '100%'
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
