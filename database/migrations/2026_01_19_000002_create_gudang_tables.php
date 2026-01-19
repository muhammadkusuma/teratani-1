<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gudang', function (Blueprint $table) {
            $table->id('id_gudang');
            $table->string('nama_gudang');
            $table->string('lokasi')->nullable();
            $table->foreignId('id_toko')->nullable()->constrained('toko', 'id_toko')->onDelete('cascade');
            $table->foreignId('id_perusahaan')->nullable()->constrained('perusahaan', 'id_perusahaan')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('stok_gudang', function (Blueprint $table) {
            $table->id('id_stok_gudang');
            $table->foreignId('id_gudang')->constrained('gudang', 'id_gudang')->onDelete('cascade');
            $table->foreignId('id_produk')->constrained('produk', 'id_produk')->onDelete('cascade');
            $table->integer('stok_fisik')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_gudang');
        Schema::dropIfExists('gudang');
    }
};
