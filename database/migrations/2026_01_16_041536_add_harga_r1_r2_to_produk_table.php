<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    


    public function up(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->decimal('harga_r1', 15, 2)->nullable()->after('harga_jual_grosir');
            $table->decimal('harga_r2', 15, 2)->nullable()->after('harga_r1');
        });
    }

    


    public function down(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->dropColumn(['harga_r1', 'harga_r2']);
        });
    }
};
