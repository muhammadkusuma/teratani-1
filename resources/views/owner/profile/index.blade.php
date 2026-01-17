@extends('layouts.owner')

@section('content')
<div class="p-4">
    <div class="win98-card">
        <div class="win98-header bg-blue-800 text-white p-2 font-bold mb-4 flex items-center">
            <span class="mr-2">👤</span> Profil Saya
        </div>

        
        <div class="flex border-b border-gray-400 mb-4 gap-1">
            <button onclick="switchTab('biodata')" id="tab-btn-biodata" class="win98-btn px-4 py-2 font-bold active-tab bg-gray-300 border-b-0 relative top-[1px] z-10">
                📝 Biodata
            </button>
            <button onclick="switchTab('keamanan')" id="tab-btn-keamanan" class="win98-btn px-4 py-2 font-bold bg-gray-200 text-gray-600">
                🔒 Keamanan
            </button>
        </div>

        
        <div id="tab-content-biodata" class="block">
            <form action="{{ route('owner.profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="bg-gray-100 p-4 border border-gray-400 shadow-inner">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" value="{{ Auth::user()->nama_lengkap }}" class="win98-input w-full p-2 border border-gray-400">
                        @error('nama_lengkap') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Username (Tidak dapat diubah)</label>
                        <input type="text" value="{{ Auth::user()->username }}" class="win98-input w-full p-2 border border-gray-400 bg-gray-200 cursor-not-allowed" readonly>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                        <input type="email" name="email" value="{{ Auth::user()->email }}" class="win98-input w-full p-2 border border-gray-400">
                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">No. HP</label>
                        <input type="text" name="no_hp" value="{{ Auth::user()->no_hp }}" class="win98-input w-full p-2 border border-gray-400">
                        @error('no_hp') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="win98-btn bg-blue-600 text-white px-4 py-2 font-bold flex items-center gap-2 hover:bg-blue-700">
                            💾 Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>

        
        <div id="tab-content-keamanan" class="hidden">
            <form action="{{ route('owner.profile.update-password') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="bg-gray-100 p-4 border border-gray-400 shadow-inner">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Password Saat Ini</label>
                        <input type="password" name="current_password" class="win98-input w-full p-2 border border-gray-400">
                        @error('current_password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Password Baru</label>
                        <input type="password" name="new_password" class="win98-input w-full p-2 border border-gray-400">
                        @error('new_password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Konfirmasi Password Baru</label>
                        <input type="password" name="new_password_confirmation" class="win98-input w-full p-2 border border-gray-400">
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="win98-btn bg-red-600 text-white px-4 py-2 font-bold flex items-center gap-2 hover:bg-red-700">
                            🔒 Update Password
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>

<script>
function switchTab(tabName) {
    // Hide all
    document.getElementById('tab-content-biodata').classList.add('hidden');
    document.getElementById('tab-content-keamanan').classList.add('hidden');
    
    document.getElementById('tab-btn-biodata').classList.remove('active-tab', 'bg-gray-300', 'z-10');
    document.getElementById('tab-btn-biodata').classList.add('bg-gray-200', 'text-gray-600');
    
    document.getElementById('tab-btn-keamanan').classList.remove('active-tab', 'bg-gray-300', 'z-10');
    document.getElementById('tab-btn-keamanan').classList.add('bg-gray-200', 'text-gray-600');

    // Show selected
    document.getElementById('tab-content-' + tabName).classList.remove('hidden');
    
    // Style active button
    const activeBtn = document.getElementById('tab-btn-' + tabName);
    activeBtn.classList.remove('bg-gray-200', 'text-gray-600');
    activeBtn.classList.add('active-tab', 'bg-gray-300', 'z-10');
}
</script>

<style>
/* Custom Active Tab Styling for Win98 Feel */
.active-tab {
    border-bottom: 2px solid #d1d5db; /* Match bg-gray-300 */
    margin-bottom: -2px;
}
</style>
@endsection
