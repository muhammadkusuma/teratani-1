<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pelanggan', function (Blueprint $table) {
            // Tambahkan kolom id_toko setelah id_tenant
            // Nullable dulu untuk antisipasi data lama, tapi sebaiknya diisi
            $table->foreignId('id_toko')
                ->nullable()
                ->after('id_tenant')
                ->constrained('toko', 'id_toko')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('pelanggan', function (Blueprint $table) {
            $table->dropForeign(['id_toko']);
            $table->dropColumn('id_toko');
        });
    }
};
