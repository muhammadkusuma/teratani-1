<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();         // Contoh: app_name, maintenance_mode
            $table->string('label');                 // Contoh: Nama Aplikasi
            $table->text('value')->nullable();       // Contoh: Teratani v1.0
            $table->string('type')->default('text'); // text, number, boolean, textarea
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
};
