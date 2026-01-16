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
        Schema::create('utang_piutang_pelanggan', function (Blueprint $table) {
            $table->id('id_piutang');
            $table->foreignId('id_pelanggan')->constrained('pelanggan', 'id_pelanggan')->onDelete('cascade');
            $table->date('tanggal');
            $table->enum('jenis_transaksi', ['piutang', 'pembayaran'])->comment('piutang = tambah piutang, pembayaran = terima bayar');
            $table->decimal('nominal', 15, 2);
            $table->text('keterangan')->nullable();
            $table->string('no_referensi', 50)->nullable()->comment('No Invoice, Nota, atau referensi lain');
            $table->decimal('saldo_piutang', 15, 2)->default(0)->comment('Running balance piutang');
            $table->timestamps();
            
            // Index untuk performa
            $table->index(['id_pelanggan', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utang_piutang_pelanggan');
    }
};
