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
        Schema::table('utang_piutang_distributor', function (Blueprint $table) {
            if (!Schema::hasColumn('utang_piutang_distributor', 'tanggal_jatuh_tempo')) {
                $table->date('tanggal_jatuh_tempo')->nullable()->after('tanggal')->comment('Tanggal jatuh tempo pembayaran (hanya untuk utang)');
            }
            
            // Add index for efficient querying of overdue payments
            $table->index(['tanggal_jatuh_tempo', 'jenis_transaksi'], 'idx_jatuh_tempo_jenis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('utang_piutang_distributor', function (Blueprint $table) {
            $table->dropIndex('idx_jatuh_tempo_jenis');
            $table->dropColumn('tanggal_jatuh_tempo');
        });
    }
};
