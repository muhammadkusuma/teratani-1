<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    


    public function up(): void
    {
        Schema::table('pelanggan', function (Blueprint $table) {
            $table->enum('kategori_harga', ['umum', 'grosir', 'r1', 'r2'])
                  ->default('umum')
                  ->after('limit_piutang')
                  ->comment('Kategori harga: umum=eceran, grosir, r1=langganan R1, r2=langganan R2');
        });
    }

    


    public function down(): void
    {
        Schema::table('pelanggan', function (Blueprint $table) {
            $table->dropColumn('kategori_harga');
        });
    }
};
