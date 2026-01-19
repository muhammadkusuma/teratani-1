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
        Schema::create('riwayat_stok', function (Blueprint $table) {
            $table->id('id_riwayat');
            $table->foreignId('id_produk')->constrained('produk', 'id_produk')->onDelete('cascade');
            
            // Nullable because movement can be from/to Toko OR Gudang
            $table->unsignedBigInteger('id_gudang')->nullable();
            $table->unsignedBigInteger('id_toko')->nullable();
            
            $table->enum('jenis', ['masuk', 'keluar']); // masuk = in, keluar = out
            $table->integer('jumlah'); // Always positive
            $table->integer('stok_akhir')->nullable(); // Snapshot of stock after movement
            $table->string('keterangan')->nullable();
            $table->string('referensi')->nullable(); // e.g. "PENJUALAN-123", "PEMBELIAN-456"
            $table->date('tanggal');
            $table->timestamps();

            $table->foreign('id_gudang')->references('id_gudang')->on('gudang')->onDelete('cascade');
            $table->foreign('id_toko')->references('id_toko')->on('toko')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_stok');
    }
};
