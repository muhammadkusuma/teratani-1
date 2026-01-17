<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pendapatan_pasif', function (Blueprint $table) {
            $table->foreignId('id_penjualan')->nullable()->after('id_user')->constrained('penjualan', 'id_penjualan')->onDelete('cascade');
            $table->boolean('is_otomatis')->default(false)->after('keterangan');
        });
        
        

        DB::statement("ALTER TABLE pendapatan_pasif MODIFY kategori ENUM('Penjualan', 'Bunga Bank', 'Sewa Aset', 'Komisi', 'Investasi', 'Lainnya')");
    }

    public function down()
    {
        Schema::table('pendapatan_pasif', function (Blueprint $table) {
            $table->dropForeign(['id_penjualan']);
            $table->dropColumn(['id_penjualan', 'is_otomatis']);
        });
        
        DB::statement("ALTER TABLE pendapatan_pasif MODIFY kategori ENUM('Bunga Bank', 'Sewa Aset', 'Komisi', 'Investasi', 'Lainnya')");
    }
};
