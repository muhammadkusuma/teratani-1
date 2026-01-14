<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'app_name', 'value' => 'Sistem Toko Tani', 'label' => 'Nama Aplikasi'],
            ['key' => 'app_tagline', 'value' => 'Sistem Manajemen Toko Pertanian', 'label' => 'Tagline Aplikasi'],
            ['key' => 'company_name', 'value' => 'Sistem Toko Tani', 'label' => 'Nama Perusahaan'],
            ['key' => 'company_address', 'value' => 'Jl. Raya Pertanian No. 123, Malang', 'label' => 'Alamat Perusahaan'],
            ['key' => 'company_phone', 'value' => '0341-123456', 'label' => 'Telepon Perusahaan'],
            ['key' => 'company_email', 'value' => 'info@tokotani.com', 'label' => 'Email Perusahaan'],
            ['key' => 'tax_percentage', 'value' => '11', 'label' => 'Persentase Pajak'],
            ['key' => 'currency', 'value' => 'IDR', 'label' => 'Mata Uang'],
            ['key' => 'timezone', 'value' => 'Asia/Jakarta', 'label' => 'Zona Waktu'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
