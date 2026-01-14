<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::create([
            'nama_bisnis' => 'Toko Tani Sejahtera',
            'kode_unik_tenant' => 'tts001',
            'alamat_kantor_pusat' => 'Jl. Raya Pertanian No. 123, Malang',
            'owner_contact' => '081234567891',
            'paket_layanan' => 'Pro',
            'max_toko' => 5,
            'status_langganan' => 'Aktif',
            'tgl_expired' => now()->addYear(),
        ]);

        $owner = User::where('username', 'owner')->first();
        $kasir = User::where('username', 'kasir1')->first();

        DB::table('user_tenant_mapping')->insert([
            [
                'id_user' => $owner->id_user,
                'id_tenant' => $tenant->id_tenant,
                'role_in_tenant' => 'OWNER',
                'is_primary' => true,
            ],
            [
                'id_user' => $kasir->id_user,
                'id_tenant' => $tenant->id_tenant,
                'role_in_tenant' => 'KASIR',
                'is_primary' => false,
            ],
        ]);
    }
}
