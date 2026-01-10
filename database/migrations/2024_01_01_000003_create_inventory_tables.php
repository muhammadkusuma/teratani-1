<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 13. Stok Toko (Summary)
        Schema::create('stok_toko', function (Blueprint $table) {
            $table->id('id_stok');
            $table->foreignId('id_toko')->constrained('toko', 'id_toko')->onDelete('cascade');
            $table->foreignId('id_produk')->constrained('produk', 'id_produk')->onDelete('cascade');
            $table->integer('stok_fisik')->default(0);
            $table->integer('stok_minimal')->default(5);
            $table->string('lokasi_rak', 50)->nullable();
            $table->timestamps(); // Created_at & Updated_at

            $table->unique(['id_toko', 'id_produk']);
        });

        // 14. Stok Batch (Expired Date Tracker)
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

        // 15. Mutasi Stok (Header)
        Schema::create('mutasi_stok', function (Blueprint $table) {
            $table->id('id_mutasi');
            $table->foreignId('id_tenant')->constrained('tenants', 'id_tenant');
            $table->string('no_mutasi', 50);

            // Relasi ke toko asal dan tujuan
            $table->foreignId('id_toko_asal')->constrained('toko', 'id_toko');
            $table->foreignId('id_toko_tujuan')->constrained('toko', 'id_toko');

            $table->dateTime('tgl_kirim')->useCurrent();
            $table->dateTime('tgl_terima')->nullable();
            $table->enum('status', ['Proses', 'Dikirim', 'Diterima', 'Batal'])->default('Proses');
            $table->text('keterangan')->nullable();

            $table->unsignedBigInteger('id_user_pengirim')->nullable();
            $table->unsignedBigInteger('id_user_penerima')->nullable();
        });

        // 16. Mutasi Detail
        Schema::create('mutasi_detail', function (Blueprint $table) {
            $table->id('id_mutasi_detail');
            $table->foreignId('id_mutasi')->constrained('mutasi_stok', 'id_mutasi')->onDelete('cascade');
            $table->foreignId('id_produk')->constrained('produk', 'id_produk');
            $table->integer('qty_kirim');
            $table->integer('qty_terima')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('mutasi_detail');
        Schema::dropIfExists('mutasi_stok');
        Schema::dropIfExists('stok_batch');
        Schema::dropIfExists('stok_toko');
    }
};
