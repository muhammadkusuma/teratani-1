<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        

        Schema::table('toko', function (Blueprint $table) {
            $table->unsignedBigInteger('id_perusahaan')->nullable()->after('id_toko');
        });

        

        $tokoCount = DB::table('toko')->count();
        if ($tokoCount > 0) {
            $defaultCompanyId = DB::table('perusahaan')->insertGetId([
                'nama_perusahaan' => 'Perusahaan Default',
                'alamat' => 'Alamat Perusahaan',
                'kota' => 'Kota',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            

            DB::table('toko')->update(['id_perusahaan' => $defaultCompanyId]);
        }

        

        Schema::table('toko', function (Blueprint $table) {
            $table->unsignedBigInteger('id_perusahaan')->nullable(false)->change();
            $table->foreign('id_perusahaan')
                  ->references('id_perusahaan')
                  ->on('perusahaan')
                  ->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::table('toko', function (Blueprint $table) {
            $table->dropForeign(['id_perusahaan']);
            $table->dropColumn('id_perusahaan');
        });
    }
};
