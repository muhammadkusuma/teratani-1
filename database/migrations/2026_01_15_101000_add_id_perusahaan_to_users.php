<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Add the column as nullable first
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('id_perusahaan')->nullable()->after('id_user');
        });

        // Assign all existing users to the first company
        $firstCompanyId = DB::table('perusahaan')->first()->id_perusahaan ?? null;
        if ($firstCompanyId) {
            DB::table('users')->update(['id_perusahaan' => $firstCompanyId]);
        }

        // Now make the column non-nullable and add foreign key
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('id_perusahaan')->nullable(false)->change();
            $table->foreign('id_perusahaan')
                  ->references('id_perusahaan')
                  ->on('perusahaan')
                  ->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['id_perusahaan']);
            $table->dropColumn('id_perusahaan');
        });
    }
};
