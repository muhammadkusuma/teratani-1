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
        Schema::table('retur_pembelian', function (Blueprint $table) {
            $table->foreignId('id_toko')->nullable()->after('id_gudang')->constrained('toko', 'id_toko')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('retur_pembelian', function (Blueprint $table) {
            $table->dropForeign(['id_toko']);
            $table->dropColumn('id_toko');
        });
    }
};
