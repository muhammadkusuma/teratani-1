<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('karyawan', function (Blueprint $table) {
            $table->id('id_karyawan');
            $table->foreignId('id_toko')->constrained('toko', 'id_toko')->onDelete('cascade');
            $table->string('kode_karyawan', 20)->unique();
            $table->string('nik', 20)->nullable();
            $table->string('nama_lengkap', 100);
            $table->string('tempat_lahir', 50)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->text('alamat')->nullable();
            $table->string('no_hp', 20);
            $table->string('email', 100)->nullable();
            $table->string('jabatan', 50);
            $table->date('tanggal_masuk');
            $table->date('tanggal_keluar')->nullable();
            $table->enum('status_karyawan', ['Aktif', 'Cuti', 'Resign'])->default('Aktif');
            $table->decimal('gaji_pokok', 15, 2)->default(0);
            $table->string('foto')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('karyawan');
    }
};
