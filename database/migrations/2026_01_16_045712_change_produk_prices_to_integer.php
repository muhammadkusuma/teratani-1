<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    


    public function up(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->integer('harga_beli')->change();
            $table->integer('harga_jual_umum')->change();
            $table->integer('harga_jual_grosir')->nullable()->change();
            $table->integer('harga_r1')->nullable()->change();
            $table->integer('harga_r2')->nullable()->change();
        });
    }

    


    public function down(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            

            $table->decimal('harga_beli', 12, 2)->change();
            $table->decimal('harga_jual_umum', 12, 2)->change();
            $table->decimal('harga_jual_grosir', 12, 2)->nullable()->change();
            $table->decimal('harga_r1', 12, 2)->nullable()->change();
            $table->decimal('harga_r2', 12, 2)->nullable()->change();
        });
    }
};
