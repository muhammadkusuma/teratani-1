<!DOCTYPE html>
<html lang="id" class="h-full bg-white">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk</title>

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

<body class="h-full font-sans text-gray-900 antialiased">

    <div class="flex min-h-full">
        {{-- Left Side - Image (Hidden on Mobile) --}}
        <div class="relative hidden w-0 flex-1 lg:block">
            <img class="absolute inset-0 h-full w-full object-cover"
                src="https://images.unsplash.com/photo-1500382017468-9049fed747ef?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80"
                alt="Sawah Hijau">

            <div class="absolute inset-0 bg-green-900/40 mix-blend-multiply"></div>
            <div class="absolute inset-0 flex flex-col justify-end p-8 lg:p-12 text-white">
                <h2 class="text-3xl lg:text-4xl font-bold mb-3 lg:mb-4">Kelola Usaha Tani Lebih Cerdas</h2>
                <p class="text-base lg:text-lg text-green-100 max-w-lg">Sistem manajemen stok dan kasir terintegrasi untuk kemajuan
                    toko pertanian Anda.</p>
            </div>
        </div>

        {{-- Right Side - Login Form --}}
        <div class="flex flex-1 flex-col justify-center px-6 py-8 sm:px-8 lg:flex-none lg:px-16 xl:px-24 bg-white">
            <div class="mx-auto w-full max-w-sm lg:w-96">
                {{-- Header --}}
                <div class="mb-8 lg:mb-10">
                    {{-- Mobile Logo/Brand (Optional) --}}
                    <div class="mb-6 lg:hidden text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-3">
                            <span class="text-3xl">🌾</span>
                        </div>
                    </div>
                    
                    <h2 class="text-2xl sm:text-3xl font-bold leading-9 tracking-tight text-gray-900 text-center lg:text-left">
                        Selamat Datang Kembali
                    </h2>
                    <p class="mt-2 text-sm sm:text-base leading-6 text-gray-500 text-center lg:text-left">
                        Silakan masuk ke akun Anda
                    </p>
                </div>

                {{-- Error Messages --}}
                @if ($errors->any())
                    <div class="rounded-lg bg-red-50 p-4 mb-6 border border-red-200 shadow-sm">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-bold text-red-800">Login Gagal</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Login Form --}}
                <form action="{{ route('login') }}" method="POST" class="space-y-5 sm:space-y-6">
                    @csrf

                    {{-- Username Field --}}
                    <div>
                        <label for="username"
                            class="block text-sm font-semibold leading-6 text-gray-900 mb-2">Username</label>
                        <input id="username" name="username" type="text" autocomplete="username" required
                            value="{{ old('username') }}"
                            class="block w-full rounded-lg border-0 py-3 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 text-base sm:text-sm transition-all"
                            placeholder="Masukkan username">
                    </div>

                    {{-- Password Field --}}
                    <div>
                        <label for="password"
                            class="block text-sm font-semibold leading-6 text-gray-900 mb-2">Password</label>
                        <input id="password" name="password" type="password" autocomplete="current-password"
                            required
                            class="block w-full rounded-lg border-0 py-3 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 text-base sm:text-sm transition-all"
                            placeholder="Masukkan password">
                    </div>

                    {{-- Remember Me & Forgot Password --}}
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-0">
                        <div class="flex items-center">
                            <input id="remember-me" name="remember" type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 text-green-600 focus:ring-green-600">
                            <label for="remember-me" class="ml-2 block text-sm leading-6 text-gray-700">Ingat
                                saya</label>
                        </div>
                        <div class="text-sm">
                            <a href="#" class="font-semibold text-green-600 hover:text-green-500 transition-colors">Lupa
                                password?</a>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="pt-2">
                        <button type="submit"
                            class="flex w-full justify-center rounded-lg bg-green-600 px-4 py-3.5 sm:py-3 text-base sm:text-sm font-bold leading-6 text-white shadow-lg hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600 transition duration-200 active:scale-[0.98]">
                            Masuk
                        </button>
                    </div>
                </form>

                {{-- Mobile Footer --}}
                <div class="mt-8 lg:hidden text-center text-xs text-gray-400">
                <p>© {{ date('Y') }} Sistem Toko Tani</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
