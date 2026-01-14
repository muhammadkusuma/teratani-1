<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'username' => 'superadmin',
            'password' => Hash::make('password'),
            'nama_lengkap' => 'Super Administrator',
            'email' => 'superadmin@tokotani.com',
            'no_hp' => '081234567890',
            'is_superadmin' => true,
            'is_active' => true,
        ]);

        User::create([
            'username' => 'owner',
            'password' => Hash::make('password'),
            'nama_lengkap' => 'Budi Santoso',
            'email' => 'owner@tokotani.com',
            'no_hp' => '081234567891',
            'is_superadmin' => false,
            'is_active' => true,
        ]);

        User::create([
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
