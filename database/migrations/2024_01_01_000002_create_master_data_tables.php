<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('toko', function (Blueprint $table) {
            $table->id('id_toko');
            $table->string('kode_toko', 20)->unique();
            $table->string('nama_toko', 100);
            $table->text('alamat')->nullable();
            $table->string('kota', 50)->nullable();
            $table->string('no_telp', 20)->nullable();
            $table->text('info_rekening')->nullable();
            $table->boolean('is_pusat')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('kategori', function (Blueprint $table) {
            $table->id('id_kategori');
            $table->string('nama_kategori', 50);
            $table->timestamps();
        });

        Schema::create('satuan', function (Blueprint $table) {
            $table->id('id_satuan');
            $table->string('nama_satuan', 20);
            $table->timestamps();
        });

        Schema::create('produk', function (Blueprint $table) {
            $table->id('id_produk');
            $table->string('sku', 50)->unique();
            $table->string('barcode', 50)->nullable()->unique();
            $table->string('nama_produk', 150);
            $table->foreignId('id_kategori')->nullable()->constrained('kategori', 'id_kategori')->onDelete('set null');
            $table->foreignId('id_satuan_kecil')->constrained('satuan', 'id_satuan')->onDelete('restrict');
            $table->foreignId('id_satuan_besar')->nullable()->constrained('satuan', 'id_satuan')->onDelete('set null');
            $table->integer('nilai_konversi')->nullable();
            $table->decimal('harga_beli', 15, 2)->default(0);
            $table->decimal('harga_jual_umum', 15, 2)->default(0);
            $table->decimal('harga_jual_grosir', 15, 2)->nullable();
            $table->string('gambar_produk')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('pelanggan', function (Blueprint $table) {
            $table->id('id_pelanggan');
            $table->foreignId('id_toko')->constrained('toko', 'id_toko')->onDelete('cascade');
            $table->string('kode_pelanggan', 30)->unique();
            $table->string('nama_pelanggan', 100);
            $table->string('no_hp', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->string('wilayah', 50)->nullable();
            $table->decimal('limit_piutang', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pelanggan');
        Schema::dropIfExists('produk');
        Schema::dropIfExists('satuan');
        Schema::dropIfExists('kategori');
        Schema::dropIfExists('toko');
    }
};
