<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Get the first company (created by PerusahaanSeeder)
        $idPerusahaan = \App\Models\Perusahaan::first()->id_perusahaan;

        User::create([
            'id_perusahaan' => $idPerusahaan,
            'username' => 'superadmin',
            'password' => Hash::make('password'),
            'nama_lengkap' => 'Super Administrator',
            'email' => 'superadmin@tokotani.com',
            'no_hp' => '081234567890',
            'is_superadmin' => true,
            'is_active' => true,
        ]);

        // Get random Karyawans to link
        $karyawanOwner = \App\Models\Karyawan::where('jabatan', 'Manager')->first(); // Assume Manager = Owner for now, or create Owner role
        $karyawanKasir = \App\Models\Karyawan::where('jabatan', 'Kasir')->first();

        User::create([
            'id_perusahaan' => $idPerusahaan,
            'id_karyawan'   => $karyawanOwner->id_karyawan ?? null,
            'username' => 'owner',
            'password' => Hash::make('password'),
            'nama_lengkap' => 'Budi Santoso',
            'email' => 'owner@tokotani.com',
            'no_hp' => '081234567891',
            'is_superadmin' => false, // Owner is NOT superadmin in this context
            'is_active' => true,
        ]);

        User::create([
            'id_perusahaan' => $idPerusahaan,
            'id_karyawan'   => $karyawanKasir->id_karyawan ?? null,
            'username' => 'kasir1',
            'password' => Hash::make('password'),
            'nama_lengkap' => 'Siti Aminah',
            'email' => 'kasir1@tokotani.com',
            'no_hp' => '081234567892',
            'is_superadmin' => false,
            'is_active' => true,
        ]);
    }
}
