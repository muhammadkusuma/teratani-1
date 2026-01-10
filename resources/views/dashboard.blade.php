@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Selamat Datang, {{ Auth::user()->nama_lengkap }}!</h2>
        <p class="text-gray-600">Berikut adalah ringkasan data sistem Teratani hari ini.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-500 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Total User</p>
                    <p class="text-lg font-bold text-gray-800">1,257</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-500 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Active Tenants</p>
                    <p class="text-lg font-bold text-gray-800">42</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-500 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Pendapatan Bulan Ini</p>
                    <p class="text-lg font-bold text-gray-800">Rp 45.2Jt</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-red-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-500 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Laporan Isu</p>
                    <p class="text-lg font-bold text-gray-800">3</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-gray-800">Aktivitas Terbaru</h3>
            <button class="text-sm text-green-600 hover:text-green-800 font-medium">Lihat Semua</button>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 bg-gray-50 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                User</th>
                            <th class="py-2 px-4 bg-gray-50 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Role</th>
                            <th class="py-2 px-4 bg-gray-50 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Waktu</th>
                            <th class="py-2 px-4 bg-gray-50 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-100">
                        <tr>
                            <td class="py-3 px-4">Budi Santoso</td>
                            <td class="py-3 px-4">Admin Tenant</td>
                            <td class="py-3 px-4">2 menit yang lalu</td>
                            <td class="py-3 px-4"><span
                                    class="px-2 py-1 text-xs font-semibold text-green-600 bg-green-100 rounded-full">Login</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4">Siti Aminah</td>
                            <td class="py-3 px-4">User</td>
                            <td class="py-3 px-4">15 menit yang lalu</td>
                            <td class="py-3 px-4"><span
                                    class="px-2 py-1 text-xs font-semibold text-blue-600 bg-blue-100 rounded-full">Update
                                    Profil</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
