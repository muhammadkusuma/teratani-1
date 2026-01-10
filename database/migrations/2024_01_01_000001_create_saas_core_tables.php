<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Users
        // Schema::create('users', function (Blueprint $table) {
        //     $table->id('id_user');
        //     $table->string('username', 50)->unique();
        //     $table->string('password');
        //     $table->string('nama_lengkap', 100)->nullable();
        //     $table->string('email', 100)->unique()->nullable();
        //     $table->string('no_hp', 20)->nullable();
        //     $table->boolean('is_superadmin')->default(false);
        //     $table->boolean('is_active')->default(true);
        //     $table->timestamps();
        // });

        // 2. Tenants
        Schema::create('tenants', function (Blueprint $table) {
            $table->id('id_tenant');
            $table->string('nama_bisnis', 100);
            $table->string('kode_unik_tenant', 20)->unique()->nullable();
            $table->text('alamat_kantor_pusat')->nullable();
            $table->string('owner_contact', 20)->nullable();
            $table->enum('paket_layanan', ['Trial', 'Basic', 'Pro', 'Enterprise'])->default('Trial');
            $table->integer('max_toko')->default(1);
            $table->enum('status_langganan', ['Aktif', 'Suspend', 'Expired'])->default('Aktif');
            $table->date('tgl_expired')->nullable();
            $table->timestamps();
        });

        // 3. SaaS Invoices
        Schema::create('saas_invoices', function (Blueprint $table) {
            $table->id('id_invoice');
            $table->foreignId('id_tenant')->constrained('tenants', 'id_tenant')->onDelete('cascade');
            $table->string('no_invoice', 50)->unique()->nullable();
            $table->date('tgl_tagihan')->nullable();
            $table->decimal('total_tagihan', 15, 2)->nullable();
            $table->enum('status_bayar', ['Unpaid', 'Paid', 'Cancelled'])->default('Unpaid');
            $table->date('periode_mulai')->nullable();
            $table->date('periode_berakhir')->nullable();
            $table->timestamps();
        });

        // 4. User Tenant Mapping
        Schema::create('user_tenant_mapping', function (Blueprint $table) {
            $table->id('id_mapping');
            $table->foreignId('id_user')->constrained('users', 'id_user')->onDelete('cascade');
            $table->foreignId('id_tenant')->constrained('tenants', 'id_tenant')->onDelete('cascade');
            $table->enum('role_in_tenant', ['OWNER', 'MANAGER', 'ADMIN', 'KASIR']);
            $table->boolean('is_primary')->default(false);

            // Mencegah duplikasi user di tenant yang sama
            $table->unique(['id_user', 'id_tenant']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_tenant_mapping');
        Schema::dropIfExists('saas_invoices');
        Schema::dropIfExists('tenants');
        // Schema::dropIfExists('users');
    }
};
