<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stok_toko', function (Blueprint $table) {
            $table->id('id_stok');
            $table->foreignId('id_toko')->constrained('toko', 'id_toko')->onDelete('cascade');
            $table->foreignId('id_produk')->constrained('produk', 'id_produk')->onDelete('cascade');
            $table->integer('stok_fisik')->default(0);
            $table->integer('stok_minimal')->default(5);
            $table->string('lokasi_rak', 50)->nullable();
            $table->timestamps();
            $table->unique(['id_toko', 'id_produk']);
        });

        Schema::create('stok_batch', function (Blueprint $table) {
            $table->id('id_batch');
            $table->foreignId('id_toko')->constrained('toko', 'id_toko')->onDelete('cascade');
            $table->foreignId('id_produk')->constrained('produk', 'id_produk')->onDelete('cascade');
            $table->string('kode_batch', 50)->nullable();
            $table->date('tgl_expired')->nullable();
            $table->integer('stok_tersedia')->default(0);
            $table->date('tgl_masuk')->default(now());
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stok_batch');
        Schema::dropIfExists('stok_toko');
    }
};
