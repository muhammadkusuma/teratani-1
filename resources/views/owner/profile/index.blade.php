@extends('layouts.owner')

@section('title', 'Profil Saya')

@section('content')
<div class="mx-auto max-w-4xl p-2 md:p-4">
    {{-- Window Container --}}
    <div class="win98-border bg-[#c0c0c0] p-1 shadow-xl">
        
        {{-- Window Header --}}
        <div class="bg-gradient-to-r from-[#000080] to-[#1084d0] text-white px-2 py-1 flex items-center justify-between mb-4">
            <div class="font-bold tracking-wide flex items-center gap-2">
                <span class="text-lg">👤</span> Profil Pengguna
            </div>
            <div class="flex gap-1">
                <button class="win98-button min-w-[20px] h-[20px] flex items-center justify-center text-xs p-0">?</button>
                <button class="win98-button min-w-[20px] h-[20px] flex items-center justify-center text-xs p-0">X</button>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="flex px-2 gap-1 overflow-x-auto">
            <button onclick="switchTab('biodata')" id="tab-btn-biodata" 
                class="px-4 py-1.5 font-bold text-sm md:text-base border-t-2 border-l-2 border-r-2 border-white border-b-0 bg-[#c0c0c0] relative top-[2px] z-10 focus:outline-none">
                📝 Biodata
            </button>
            <button onclick="switchTab('keamanan')" id="tab-btn-keamanan" 
                class="px-4 py-1.5 font-bold text-sm md:text-base border-t-2 border-l-2 border-r-2 border-white border-b-0 bg-[#c0c0c0] text-gray-600 relative top-[2px] focus:outline-none hover:bg-gray-200">
                🔒 Keamanan
            </button>
        </div>

        {{-- Tab Content Container --}}
        <div class="border-t-2 border-white border-l-2 border-gray-600 border-r-2 border-gray-600 border-b-2 border-gray-600 p-4 bg-[#c0c0c0] min-h-[300px]">
            
            {{-- Biodata Tab --}}
            <div id="tab-content-biodata" class="block">
                <form action="{{ route('owner.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <fieldset class="border-2 border-white border-t-gray-600 border-l-gray-600 p-4 mb-4">
                        <legend class="px-2 font-bold mb-2">Informasi Pribadi</legend>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="mb-2">
                                <label class="block text-sm font-bold mb-1">Nama Lengkap:</label>
                                <input type="text" name="nama_lengkap" value="{{ Auth::user()->nama_lengkap }}" 
                                    class="win98-input w-full">
                                @error('nama_lengkap') <span class="text-red-600 text-xs bg-red-100 px-1">{{ $message }}</span> @enderror
                            </div>
        
                            <div class="mb-2">
                                <label class="block text-sm font-bold mb-1">Username:</label>
                                <input type="text" value="{{ Auth::user()->username }}" 
                                    class="win98-input w-full bg-gray-200 text-gray-500 cursor-not-allowed" readonly>
                                <p class="text-xs text-gray-600 mt-1">*Username tidak dapat diubah</p>
                            </div>
        
                            <div class="mb-2">
                                <label class="block text-sm font-bold mb-1">Email:</label>
                                <input type="email" name="email" value="{{ Auth::user()->email }}" 
                                    class="win98-input w-full">
                                @error('email') <span class="text-red-600 text-xs bg-red-100 px-1">{{ $message }}</span> @enderror
                            </div>
        
                            <div class="mb-2">
                                <label class="block text-sm font-bold mb-1">No. HP / WhatsApp:</label>
                                <input type="text" name="no_hp" value="{{ Auth::user()->no_hp }}" 
                                    class="win98-input w-full">
                                @error('no_hp') <span class="text-red-600 text-xs bg-red-100 px-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </fieldset>

                    <div class="flex justify-end gap-2 mt-4">
                        <button type="submit" class="win98-button flex items-center gap-2 active:translate-x-[1px] active:translate-y-[1px]">
                            <span class="text-green-800">💾</span> Simpan Biodata
                        </button>
                    </div>
                </form>
            </div>

            {{-- Keamanan Tab --}}
            <div id="tab-content-keamanan" class="hidden">
                <form action="{{ route('owner.profile.update-password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <fieldset class="border-2 border-white border-t-gray-600 border-l-gray-600 p-4 mb-4">
                        <legend class="px-2 font-bold mb-2">Ganti Password</legend>

                        <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-3 py-2 text-sm mb-4">
                            ⚠ Pastikan password baru Anda kuat dan mudah diingat.
                        </div>

                        <div class="space-y-4 max-w-md mx-auto md:mx-0">
                            <div>
                                <label class="block text-sm font-bold mb-1">Password Saat Ini:</label>
                                <input type="password" name="current_password" class="win98-input w-full">
                                @error('current_password') <span class="text-red-600 text-xs bg-red-100 px-1">{{ $message }}</span> @enderror
                            </div>
    
                            <div>
                                <label class="block text-sm font-bold mb-1">Password Baru:</label>
                                <input type="password" name="new_password" class="win98-input w-full">
                                @error('new_password') <span class="text-red-600 text-xs bg-red-100 px-1">{{ $message }}</span> @enderror
                            </div>
    
                            <div>
                                <label class="block text-sm font-bold mb-1">Konfirmasi Password Baru:</label>
                                <input type="password" name="new_password_confirmation" class="win98-input w-full">
                            </div>
                        </div>
                    </fieldset>

                    <div class="flex justify-end gap-2 mt-4">
                        <button type="submit" class="win98-button flex items-center gap-2 active:translate-x-[1px] active:translate-y-[1px]">
                            <span class="text-red-800">🔒</span> Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Status Bar --}}
        <div class="border-t border-gray-400 mt-2 pt-1 text-sm text-gray-600 flex justify-between px-2">
            <span>{{ count(Auth::user()->perusahaan->tokos ?? []) }} Toko Terhubung</span>
            <span>ID: {{ Auth::user()->username }}</span>
        </div>
    </div>
</div>

<script>
function switchTab(tabName) {
    // Hide all contents
    document.getElementById('tab-content-biodata').classList.add('hidden');
    document.getElementById('tab-content-keamanan').classList.add('hidden');
    
    // Reset buttons
    const btnBiodata = document.getElementById('tab-btn-biodata');
    const btnKeamanan = document.getElementById('tab-btn-keamanan');

    // Inactive style
    const inactiveClass = ['text-gray-600', 'border-b-white', 'hover:bg-gray-200', 'z-0'];
    const activeClass = ['z-10', 'border-b-[#c0c0c0]']; // z-10 to sit on top of border

    btnBiodata.classList.add(...inactiveClass);
    btnBiodata.classList.remove(...activeClass);
    
    btnKeamanan.classList.add(...inactiveClass);
    btnKeamanan.classList.remove(...activeClass);

    // Activate selected
    const activeBtn = document.getElementById('tab-btn-' + tabName);
    activeBtn.classList.remove(...inactiveClass);
    activeBtn.classList.add(...activeClass, 'text-black'); // Ensure text is black

    // Show content
    document.getElementById('tab-content-' + tabName).classList.remove('hidden');
}

// Initialize styling on load
document.addEventListener('DOMContentLoaded', () => {
    // Just force the initial state to clear up any Tailwind utility conflicts
    switchTab('biodata');
});
</script>
@endsection
