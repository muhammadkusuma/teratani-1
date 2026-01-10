@extends('layouts.admin')

@section('title', 'Edit Pengaturan')

@section('content')
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Edit Pengaturan</h2>
            <span class="text-sm font-mono bg-gray-100 px-2 py-1 rounded text-gray-600">{{ $setting->key }}</span>
        </div>

        <form action="{{ route('settings.update', $setting->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Label</label>
                <input type="text" name="label" value="{{ old('label', $setting->label) }}"
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                @error('label')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Key</label>
                <input type="text" name="key" value="{{ old('key', $setting->key) }}"
                    class="w-full px-3 py-2 border rounded-lg bg-gray-100 text-gray-600 focus:outline-none font-mono">
                @error('key')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <input type="hidden" name="type" value="{{ $setting->type }}">

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Value ({{ ucfirst($setting->type) }})</label>

                @if ($setting->type === 'boolean')
                    <select name="value"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="1" {{ $setting->value == '1' ? 'selected' : '' }}>TRUE / ON</option>
                        <option value="0" {{ $setting->value == '0' ? 'selected' : '' }}>FALSE / OFF</option>
                    </select>
                @elseif($setting->type === 'textarea')
                    <textarea name="value" rows="5"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">{{ old('value', $setting->value) }}</textarea>
                @elseif($setting->type === 'number')
                    <input type="number" name="value" value="{{ old('value', $setting->value) }}"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                @else
                    <input type="text" name="value" value="{{ old('value', $setting->value) }}"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                @endif

                @error('value')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end">
                <a href="{{ route('settings.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow">Perbarui</button>
            </div>
        </form>
    </div>
@endsection
