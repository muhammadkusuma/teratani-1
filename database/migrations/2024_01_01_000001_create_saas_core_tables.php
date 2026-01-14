<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
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

        Schema::create('user_tenant_mapping', function (Blueprint $table) {
            $table->id('id_mapping');
            $table->foreignId('id_user')->constrained('users', 'id_user')->onDelete('cascade');
            $table->foreignId('id_tenant')->constrained('tenants', 'id_tenant')->onDelete('cascade');
            $table->enum('role_in_tenant', ['OWNER', 'MANAGER', 'ADMIN', 'KASIR']);
            $table->boolean('is_primary')->default(false);

            $table->unique(['id_user', 'id_tenant']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_tenant_mapping');
        Schema::dropIfExists('tenants');
    }
};
