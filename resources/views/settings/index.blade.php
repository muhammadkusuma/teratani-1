@extends('layouts.admin')

@section('title', 'Pengaturan Sistem')

@section('content')
    <div class="container mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Pengaturan Sistem</h2>
            <a href="{{ route('settings.create') }}"
                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow">
                + Tambah Key Baru
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-4 border-b">
                <form action="{{ route('settings.index') }}" method="GET">
                    <input type="text" name="search" placeholder="Cari pengaturan..." value="{{ request('search') }}"
                        class="w-full md:w-1/3 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                </form>
            </div>

            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th
                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Label & Key</th>
                        <th
                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Value</th>
                        <th
                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Type</th>
                        <th
                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($settings as $setting)
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 font-bold">{{ $setting->label }}</p>
                                <code
                                    class="text-gray-500 text-xs bg-gray-100 px-1 py-0.5 rounded">{{ $setting->key }}</code>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                @if ($setting->type == 'boolean')
                                    <span
                                        class="px-2 py-1 text-xs font-semibold {{ $setting->value == '1' ? 'text-green-700 bg-green-100' : 'text-red-700 bg-red-100' }} rounded-full">
                                        {{ $setting->value == '1' ? 'TRUE' : 'FALSE' }}
                                    </span>
                                @else
                                    {{ Str::limit($setting->value, 50) }}
                                @endif
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-gray-600 uppercase text-xs">
                                {{ $setting->type }}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('settings.edit', $setting->id) }}"
                                        class="text-blue-600 hover:text-blue-900 font-medium">Edit</a>
                                    <form action="{{ route('settings.destroy', $setting->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus pengaturan ini? Mungkin akan mempengaruhi fitur aplikasi.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-900 font-medium">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4"
                                class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center text-gray-500">
                                Belum ada data pengaturan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-5 py-5 bg-white border-t">
                {{ $settings->links() }}
            </div>
        </div>
    </div>
@endsection
