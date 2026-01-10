<?php
namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    /**
     * Menampilkan daftar pengaturan.
     */
    public function index(Request $request)
    {
        $query = Setting::query();

        if ($request->has('search')) {
            $query->where('label', 'like', "%{$request->search}%")
                ->orWhere('key', 'like', "%{$request->search}%");
        }

        $settings = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('settings.index', compact('settings'));
    }

    /**
     * Form tambah pengaturan baru.
     */
    public function create()
    {
        return view('settings.create');
    }

    /**
     * Simpan pengaturan baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'key'   => 'required|alpha_dash|unique:settings,key',
            'label' => 'required|string|max:100',
            'value' => 'nullable|string',
            'type'  => 'required|in:text,number,textarea,boolean',
        ]);

        Setting::create($request->all());

        return redirect()->route('settings.index')->with('success', 'Pengaturan berhasil ditambahkan.');
    }

    /**
     * Form edit pengaturan.
     */
    public function edit(Setting $setting)
    {
        return view('settings.edit', compact('setting'));
    }

    /**
     * Update pengaturan.
     */
    public function update(Request $request, Setting $setting)
    {
        $request->validate([
            'key'   => ['required', 'alpha_dash', Rule::unique('settings')->ignore($setting->id)],
            'label' => 'required|string|max:100',
            'value' => 'nullable|string',
            'type'  => 'required|in:text,number,textarea,boolean',
        ]);

        $setting->update($request->all());

        return redirect()->route('settings.index')->with('success', 'Pengaturan berhasil diperbarui.');
    }

    /**
     * Hapus pengaturan.
     */
    public function destroy(Setting $setting)
    {
        $setting->delete();
        return redirect()->route('settings.index')->with('success', 'Pengaturan berhasil dihapus.');
    }
}
