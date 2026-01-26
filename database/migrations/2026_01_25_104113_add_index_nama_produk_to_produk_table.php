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
        Schema::table('produk', function (Blueprint $table) {
             // Check if index exists before adding (Raw SQL check for safety across different drivers, though mainly MySQL)
            $indexExists = collect(DB::select("SHOW INDEXES FROM produk WHERE Key_name = 'produk_nama_produk_index'"))->count() > 0;
            
            if (!$indexExists) {
                $table->index('nama_produk');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->dropIndex(['nama_produk']);
        });
    }
};
