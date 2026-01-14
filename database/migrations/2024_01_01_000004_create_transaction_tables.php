<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 17. Pembelian (Header)
        // Schema::create('pembelian', function (Blueprint $table) {
        //     $table->id('id_pembelian');
        //     $table->foreignId('id_toko')->constrained('toko', 'id_toko');
        //     // Jika distributor dihapus, data pembelian tetap ada (Set Null)
        //     $table->foreignId('id_distributor')->nullable()->constrained('distributor', 'id_distributor')->onDelete('set null');
        //     $table->foreignId('id_user')->nullable()->constrained('users', 'id_user')->onDelete('set null');

        //     $table->string('no_faktur_supplier', 50)->nullable();
        //     $table->date('tgl_pembelian')->nullable();
        //     $table->date('tgl_jatuh_tempo')->nullable();
        //     $table->decimal('total_pembelian', 15, 2)->nullable();
        //     $table->enum('status_bayar', ['Lunas', 'Hutang', 'Sebagian'])->default('Lunas');
        //     $table->text('keterangan')->nullable();

        //     $table->softDeletes(); // deleted_at
        //     $table->timestamps();
        // });

        // 18. Pembelian Detail
        // Schema::create('pembelian_detail', function (Blueprint $table) {
        //     $table->id('id_detail_beli');
        //     $table->foreignId('id_pembelian')->constrained('pembelian', 'id_pembelian')->onDelete('cascade');
        //     $table->foreignId('id_produk')->constrained('produk', 'id_produk');

        //     $table->integer('qty');
        //     $table->string('satuan_beli', 20)->nullable();
        //     $table->decimal('harga_beli_satuan', 15, 2)->nullable();
        //     $table->decimal('subtotal', 15, 2)->nullable();
        //     $table->date('tgl_expired_item')->nullable();
        // });

        // 19. Penjualan (Header)
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id('id_penjualan');
            $table->foreignId('id_toko')->constrained('toko', 'id_toko');
            // Keamanan data: Set Null jika master dihapus
            $table->foreignId('id_user')->nullable()->constrained('users', 'id_user')->onDelete('set null');
            $table->foreignId('id_pelanggan')->nullable()->constrained('pelanggan', 'id_pelanggan')->onDelete('set null');
            $table->foreignId('id_sales')->nullable()->constrained('sales', 'id_sales')->onDelete('set null');

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

        // 20. Penjualan Detail
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

        // 21. Kartu Piutang
        // Schema::create('kartu_piutang', function (Blueprint $table) {
        //     $table->id('id_piutang');
        //     $table->foreignId('id_toko')->constrained('toko', 'id_toko');
        //     $table->foreignId('id_penjualan')->constrained('penjualan', 'id_penjualan')->onDelete('cascade');
        //     $table->foreignId('id_pelanggan')->constrained('pelanggan', 'id_pelanggan')->onDelete('restrict'); // Jangan hapus pelanggan jika ada piutang

        //     $table->date('tgl_jatuh_tempo')->nullable();
        //     $table->decimal('total_piutang', 15, 2)->nullable();
        //     $table->decimal('sudah_dibayar', 15, 2)->default(0);
        //     $table->decimal('sisa_piutang', 15, 2)->nullable();
        //     $table->enum('status', ['Lunas', 'Belum Lunas'])->default('Belum Lunas');
        //     $table->timestamps();
        // });

        // 22. Pembayaran Piutang (Cicilan)
        // Schema::create('pembayaran_piutang', function (Blueprint $table) {
        //     $table->id('id_bayar_piutang');
        //     $table->foreignId('id_piutang')->constrained('kartu_piutang', 'id_piutang')->onDelete('cascade');

        //     $table->string('no_kuitansi', 50)->nullable();
        //     $table->dateTime('tgl_bayar')->useCurrent();
        //     $table->decimal('jumlah_bayar', 15, 2);
        //     $table->enum('metode_bayar', ['Tunai', 'Transfer'])->nullable();
        //     $table->text('keterangan')->nullable();
        //     $table->integer('id_user')->nullable(); // Tidak direlasikan ketat agar cepat
        // });

        // 23. Pengeluaran
        // Schema::create('pengeluaran', function (Blueprint $table) {
        //     $table->id('id_pengeluaran');
        //     $table->foreignId('id_toko')->constrained('toko', 'id_toko');
        //     $table->foreignId('id_user')->nullable()->constrained('users', 'id_user')->onDelete('set null');

        //     $table->string('no_referensi', 50)->nullable();
        //     $table->date('tgl_pengeluaran')->nullable();
        //     $table->string('kategori_biaya', 50)->nullable();
        //     $table->decimal('nominal', 15, 2);
        //     $table->text('keterangan')->nullable();
        //     $table->string('bukti_foto')->nullable();

        //     $table->softDeletes();
        //     $table->timestamps();
        // });

        // 24. Log Stok
        // Schema::create('log_stok', function (Blueprint $table) {
        //     $table->id('id_log');
        //     $table->foreignId('id_toko')->constrained('toko', 'id_toko');
        //     $table->foreignId('id_produk')->constrained('produk', 'id_produk');
        //     $table->integer('id_user')->nullable();

        //     $table->enum('jenis_transaksi', ['Penjualan', 'Pembelian', 'Mutasi Masuk', 'Mutasi Keluar', 'Opname', 'Retur', 'Expired']);
        //     $table->string('no_referensi', 50)->nullable();
        //     $table->integer('qty_masuk')->default(0);
        //     $table->integer('qty_keluar')->default(0);
        //     $table->integer('stok_akhir');
        //     $table->text('keterangan')->nullable();
        //     $table->timestamps(); // Created At saja yang penting
        // });
    }

    public function down()
    {
        Schema::dropIfExists('log_stok');
        Schema::dropIfExists('pengeluaran');
        Schema::dropIfExists('pembayaran_piutang');
        Schema::dropIfExists('kartu_piutang');
        Schema::dropIfExists('penjualan_detail');
        Schema::dropIfExists('penjualan');
        Schema::dropIfExists('pembelian_detail');
        Schema::dropIfExists('pembelian');
    }
};
