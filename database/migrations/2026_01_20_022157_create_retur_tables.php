<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Retur Penjualan (Customer Returns)
        Schema::create('retur_penjualan', function (Blueprint $table) {
            $table->id('id_retur_penjualan');
            $table->foreignId('id_penjualan')->nullable()->constrained('penjualan', 'id_penjualan')->onDelete('set null'); // Optional link to original sale
            $table->foreignId('id_pelanggan')->constrained('pelanggan', 'id_pelanggan')->onDelete('cascade');
            $table->foreignId('id_toko')->constrained('toko', 'id_toko'); // To ensure return goes to correct store
            $table->foreignId('id_user')->nullable()->constrained('users', 'id_user')->onDelete('set null'); // Staff who processed it
            $table->date('tgl_retur');
            $table->decimal('total_retur', 15, 2)->default(0);
            $table->enum('status_retur', ['Pending', 'Selesai', 'Dibatalkan'])->default('Selesai');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('retur_penjualan_detail', function (Blueprint $table) {
            $table->id('id_retur_penjualan_detail');
            $table->foreignId('id_retur_penjualan')->constrained('retur_penjualan', 'id_retur_penjualan')->onDelete('cascade');
            $table->foreignId('id_produk')->constrained('produk', 'id_produk');
            $table->integer('qty');
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->string('alasan')->nullable(); 
            $table->timestamps();
        });

        // Retur Pembelian (Distributor Returns)
        Schema::create('retur_pembelian', function (Blueprint $table) {
            $table->id('id_retur_pembelian');
            $table->foreignId('id_pembelian')->nullable()->constrained('pembelian', 'id_pembelian')->onDelete('set null'); // Optional link to partial purchase
            $table->foreignId('id_distributor')->constrained('distributor', 'id_distributor')->onDelete('cascade');
            $table->foreignId('id_gudang')->nullable()->constrained('gudang', 'id_gudang'); // Stock source
            $table->date('tgl_retur');
            $table->decimal('total_retur', 15, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('retur_pembelian_detail', function (Blueprint $table) {
            $table->id('id_retur_pembelian_detail');
            $table->foreignId('id_retur_pembelian')->constrained('retur_pembelian', 'id_retur_pembelian')->onDelete('cascade');
            $table->foreignId('id_produk')->constrained('produk', 'id_produk');
            $table->integer('qty');
            $table->decimal('harga_satuan', 15, 2); // Purchase price at return
            $table->decimal('subtotal', 15, 2);
            $table->string('alasan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retur_pembelian_detail');
        Schema::dropIfExists('retur_pembelian');
        Schema::dropIfExists('retur_penjualan_detail');
        Schema::dropIfExists('retur_penjualan');
    }
};
