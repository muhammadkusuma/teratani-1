<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pendapatan_pasif', function (Blueprint $table) {
            $table->id('id_pendapatan');
            $table->foreignId('id_toko')->constrained('toko', 'id_toko')->onDelete('cascade');
            $table->foreignId('id_user')->nullable()->constrained('users', 'id_user')->onDelete('set null');
            $table->string('kode_pendapatan', 20)->unique();
            $table->date('tanggal_pendapatan');
            $table->enum('kategori', ['Bunga Bank', 'Sewa Aset', 'Komisi', 'Investasi', 'Lainnya']);
            $table->text('sumber');
            $table->decimal('jumlah', 15, 2);
            $table->enum('metode_terima', ['Tunai', 'Transfer'])->default('Transfer');
            $table->string('bukti_penerimaan')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pendapatan_pasif');
    }
};
