<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Teratani') - Solusi Digital Pertanian</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Inter', 'sans-serif']
                        },
                        colors: {
                            green: {
                                50: '#f0fdf4',
                                100: '#dcfce7',
                                500: '#22c55e',
                                600: '#16a34a',
                                700: '#15803d',
                                800: '#166534',
                                900: '#14532d'
                            }
                        }
                    }
                }
            }
        </script>
    @endif
</head>

<body class="font-sans antialiased text-gray-800 bg-white flex flex-col min-h-screen">

    <nav class="fixed w-full z-50 bg-white/90 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <a href="{{ url('/') }}" class="flex-shrink-0 flex items-center gap-2">
                    <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center text-white">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="font-bold text-2xl tracking-tight text-green-800">Teratani</span>
                </a>
                <div class="hidden md:flex space-x-8 items-center">
                    <a href="{{ url('/fitur') }}"
                        class="text-gray-600 hover:text-green-600 font-medium transition">Fitur</a>
                    <a href="{{ url('/harga') }}"
                        class="text-gray-600 hover:text-green-600 font-medium transition">Harga</a>
                    <a href="{{ url('/tentang-kami') }}"
                        class="text-gray-600 hover:text-green-600 font-medium transition">Tentang Kami</a>
                    <a href="{{ url('/kontak') }}"
                        class="text-gray-600 hover:text-green-600 font-medium transition">Kontak</a>
                </div>
                <div class="hidden md:flex items-center space-x-4">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="px-5 py-2.5 rounded-full bg-green-600 text-white font-medium hover:bg-green-700 transition">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}"
                            class="text-gray-600 hover:text-green-600 font-medium transition">Masuk</a>
                        <a href="{{ route('register') }}"
                            class="px-5 py-2.5 rounded-full bg-green-600 text-white font-medium hover:bg-green-700 transition">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow pt-20">
        @yield('content')
    </main>

    <footer class="bg-gray-50 border-t border-gray-200 pt-12 pb-8 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8 mb-12">
                <div class="col-span-1 md:col-span-1">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-green-600 rounded flex items-center justify-center text-white"><span
                                class="font-bold">T</span></div>
                        <span class="font-bold text-xl text-gray-900">Teratani</span>
                    </div>
                    <p class="text-gray-500 text-sm leading-relaxed">Aplikasi manajemen pertanian terintegrasi untuk
                        masa depan pangan.</p>
                </div>
                <div>
                    <h4 class="font-bold text-gray-900 mb-4">Produk</h4>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><a href="{{ url('/fitur') }}" class="hover:text-green-600">Fitur</a></li>
                        <li><a href="{{ url('/harga') }}" class="hover:text-green-600">Harga</a></li>
                        <li><a href="{{ url('/studi-kasus') }}" class="hover:text-green-600">Studi Kasus</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-gray-900 mb-4">Perusahaan</h4>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><a href="{{ url('/tentang-kami') }}" class="hover:text-green-600">Tentang Kami</a></li>
                        <li><a href="{{ url('/karir') }}" class="hover:text-green-600">Karir</a></li>
                        <li><a href="{{ url('/kontak') }}" class="hover:text-green-600">Kontak</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-gray-900 mb-4">Legal</h4>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><a href="{{ url('/privasi') }}" class="hover:text-green-600">Kebijakan Privasi</a></li>
                        <li><a href="{{ url('/syarat-ketentuan') }}" class="hover:text-green-600">Syarat &
                                Ketentuan</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-200 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-sm text-gray-400">&copy; 2026 Teratani. All rights reserved.</p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="https://facebook.com" target="_blank" class="text-gray-400 hover:text-green-600">
                        <span class="sr-only">Facebook</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>
