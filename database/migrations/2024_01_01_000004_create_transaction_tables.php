<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id('id_penjualan');
            $table->foreignId('id_toko')->constrained('toko', 'id_toko');
            $table->foreignId('id_user')->nullable()->constrained('users', 'id_user')->onDelete('set null');
            $table->foreignId('id_pelanggan')->nullable()->constrained('pelanggan', 'id_pelanggan')->onDelete('set null');

            $table->string('no_faktur', 50);
            $table->dateTime('tgl_transaksi')->useCurrent();
            $table->date('tgl_jatuh_tempo')->nullable();

            $table->decimal('total_bruto', 15, 2)->default(0);
            $table->decimal('diskon_nota', 15, 2)->default(0);
            $table->decimal('pajak_ppn', 15, 2)->default(0);
            $table->decimal('biaya_lain', 15, 2)->default(0);
            $table->decimal('total_netto', 15, 2)->default(0);
            $table->decimal('jumlah_bayar', 15, 2)->default(0);
            $table->decimal('kembalian', 15, 2)->default(0);

            $table->enum('metode_bayar', ['Tunai', 'Kredit', 'Transfer', 'QRIS', 'Hutang'])->default('Tunai');
            $table->enum('status_transaksi', ['Selesai', 'Pending', 'Batal'])->default('Selesai');
            $table->enum('status_bayar', ['Lunas', 'Belum Lunas', 'Sebagian'])->default('Lunas');
            $table->text('catatan')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->unique(['id_toko', 'no_faktur']);
        });

        Schema::create('penjualan_detail', function (Blueprint $table) {
            $table->id('id_detail');
            $table->foreignId('id_penjualan')->constrained('penjualan', 'id_penjualan')->onDelete('cascade');
            $table->foreignId('id_produk')->constrained('produk', 'id_produk');

            $table->integer('qty');
            $table->string('satuan_jual', 20)->nullable();
            $table->decimal('harga_modal_saat_jual', 15, 2)->nullable();
            $table->decimal('harga_jual_satuan', 15, 2)->nullable();
            $table->decimal('diskon_item', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('penjualan_detail');
        Schema::dropIfExists('penjualan');
    }
};
