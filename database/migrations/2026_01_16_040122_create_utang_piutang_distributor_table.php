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
        Schema::create('utang_piutang_distributor', function (Blueprint $table) {
            $table->id('id_utang_piutang');
            $table->foreignId('id_distributor')->constrained('distributor', 'id_distributor')->onDelete('cascade');
            $table->date('tanggal');
            $table->enum('jenis_transaksi', ['utang', 'pembayaran'])->comment('utang = tambah utang, pembayaran = bayar utang');
            $table->decimal('nominal', 15, 2);
            $table->text('keterangan')->nullable();
            $table->string('no_referensi', 50)->nullable()->comment('No PO, Invoice, atau referensi lain');
            $table->decimal('saldo_utang', 15, 2)->default(0)->comment('Running balance utang');
            $table->timestamps();
            
            // Index untuk performa
            $table->index(['id_distributor', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utang_piutang_distributor');
    }
};
