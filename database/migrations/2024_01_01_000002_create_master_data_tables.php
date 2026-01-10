<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 5. Toko
        Schema::create('toko', function (Blueprint $table) {
            $table->id('id_toko');
            $table->foreignId('id_tenant')->constrained('tenants', 'id_tenant')->onDelete('cascade');
            $table->string('kode_toko', 20)->nullable();
            $table->string('nama_toko', 100);
            $table->text('alamat')->nullable();
            $table->string('kota', 50)->nullable();
            $table->string('no_telp', 20)->nullable();
            $table->boolean('is_pusat')->default(false);
            $table->boolean('is_active')->default(true);
        });

        // 6. User Toko Access
        Schema::create('user_toko_access', function (Blueprint $table) {
            $table->id('id_access');
            $table->foreignId('id_user')->constrained('users', 'id_user')->onDelete('cascade');
            $table->foreignId('id_toko')->constrained('toko', 'id_toko')->onDelete('cascade');
        });

        // 7. Kategori
        Schema::create('kategori', function (Blueprint $table) {
            $table->id('id_kategori');
            $table->foreignId('id_tenant')->constrained('tenants', 'id_tenant')->onDelete('cascade');
            $table->string('nama_kategori', 50);
        });

        // 8. Satuan
        Schema::create('satuan', function (Blueprint $table) {
            $table->id('id_satuan');
            $table->foreignId('id_tenant')->constrained('tenants', 'id_tenant')->onDelete('cascade');
            $table->string('nama_satuan', 20);
        });

        // 9. Produk
        Schema::create('produk', function (Blueprint $table) {
            $table->id('id_produk');
            $table->foreignId('id_tenant')->constrained('tenants', 'id_tenant')->onDelete('cascade');
            $table->foreignId('id_kategori')->nullable()->constrained('kategori', 'id_kategori')->onDelete('set null');

            // Relasi Satuan
            $table->foreignId('id_satuan_kecil')->nullable()->constrained('satuan', 'id_satuan');
            $table->foreignId('id_satuan_besar')->nullable()->constrained('satuan', 'id_satuan');

            $table->string('sku', 50)->nullable();
            $table->string('barcode', 100)->nullable();
            $table->string('nama_produk', 150);
            $table->text('deskripsi')->nullable();

            $table->decimal('harga_beli_rata_rata', 15, 2)->default(0);
            $table->decimal('harga_jual_umum', 15, 2)->default(0);

            $table->integer('nilai_konversi')->default(1); // 1 Dus = 12 Pcs
            $table->string('gambar_produk')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['id_tenant', 'sku']);
        });

        // 10. Distributor
        Schema::create('distributor', function (Blueprint $table) {
            $table->id('id_distributor');
            $table->foreignId('id_tenant')->constrained('tenants', 'id_tenant')->onDelete('cascade');
            $table->string('nama_distributor', 100);
            $table->string('nama_kontak', 100)->nullable();
            $table->string('no_telp', 20)->nullable();
            $table->text('alamat')->nullable();
        });

        // 11. Pelanggan
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->id('id_pelanggan');
            $table->foreignId('id_tenant')->constrained('tenants', 'id_tenant')->onDelete('cascade');
            $table->string('kode_pelanggan', 20)->nullable();
            $table->string('nama_pelanggan', 100);
            $table->string('wilayah', 100)->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->decimal('limit_piutang', 15, 2)->default(0);
            $table->timestamps();
        });

        // 12. Sales
        Schema::create('sales', function (Blueprint $table) {
            $table->id('id_sales');
            $table->foreignId('id_tenant')->constrained('tenants', 'id_tenant')->onDelete('cascade');
            $table->string('nama_sales', 100);
            $table->string('no_hp', 20)->nullable();
            $table->boolean('is_active')->default(true);
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales');
        Schema::dropIfExists('pelanggan');
        Schema::dropIfExists('distributor');
        Schema::dropIfExists('produk');
        Schema::dropIfExists('satuan');
        Schema::dropIfExists('kategori');
        Schema::dropIfExists('user_toko_access');
        Schema::dropIfExists('toko');
    }
};
