<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('distributor', function (Blueprint $table) {
            $table->id('id_distributor');
            $table->foreignId('id_toko')->constrained('toko', 'id_toko')->onDelete('cascade');
            $table->string('kode_distributor', 20)->unique();
            $table->string('nama_distributor', 100);
            $table->string('nama_perusahaan', 150)->nullable();
            $table->text('alamat')->nullable();
            $table->string('kota', 50)->nullable();
            $table->string('provinsi', 50)->nullable();
            $table->string('kode_pos', 10)->nullable();
            $table->string('no_telp', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('nama_kontak', 100)->nullable();
            $table->string('no_hp_kontak', 20)->nullable();
            $table->string('npwp', 30)->nullable();
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('distributor');
    }
};
