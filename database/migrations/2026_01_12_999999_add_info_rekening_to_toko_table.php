<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('toko', function (Blueprint $table) {
            // Menambahkan kolom teks untuk info rekening (Bisa multi-baris)
            $table->text('info_rekening')->nullable()->after('no_telp');
        });
    }

    public function down()
    {
        Schema::table('toko', function (Blueprint $table) {
            $table->dropColumn('info_rekening');
        });
    }
};
