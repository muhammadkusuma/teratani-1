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

        // Create specific OWNER account
        // Try to find a Manager to link to, otherwise null (handled by Unknown role)
        $managerKaryawan = \App\Models\Karyawan::where('jabatan', 'Manager')->first();
        
        User::create([
            'id_perusahaan' => $idPerusahaan,
            'id_karyawan'   => $managerKaryawan?->id_karyawan,
            'username'      => 'owner',
            'password'      => Hash::make('password'),
            'nama_lengkap'  => 'Owner Toko',
            'email'         => 'owner@tokotani.com',
            'no_hp'         => '081299999999',
            'is_superadmin' => false,
            'is_active'     => true,
        ]);

        // Create accounts for ALL Karyawans
        $karyawans = \App\Models\Karyawan::all();
        
        foreach($karyawans as $karyawan) {
            // Check if user already exists (avoid duplicates if re-seeding without fresh)
            if(User::where('id_karyawan', $karyawan->id_karyawan)->exists()) continue;

            // Generate simple username: firstname + id (e.g., budi1, siti2)
            $firstName = strtolower(explode(' ', $karyawan->nama_lengkap)[0]);
            $username = $firstName . $karyawan->id_karyawan;

            User::create([
                'id_perusahaan' => $idPerusahaan,
                'id_karyawan'   => $karyawan->id_karyawan,
                'username'      => $username,
                'password'      => Hash::make('password'),
                'nama_lengkap'  => $karyawan->nama_lengkap,
                'email'         => $karyawan->email ?? $username . '@tokotani.com',
                'no_hp'         => $karyawan->no_hp,
                'is_superadmin' => false,
                'is_active'     => true,
            ]);
        }
    }
}
