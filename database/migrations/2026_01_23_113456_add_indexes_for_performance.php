<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Helper function to check if index exists
        $indexExists = function ($table, $indexName) {
            $indexes = DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]);
            return !empty($indexes);
        };

        // Add indexes only if they don't exist
        Schema::table('stok_gudang', function (Blueprint $table) use ($indexExists) {
            if (!$indexExists('stok_gudang', 'idx_stok_gudang_composite')) {
                $table->index(['id_gudang', 'id_produk'], 'idx_stok_gudang_composite');
            }
        });

        Schema::table('stok_toko', function (Blueprint $table) use ($indexExists) {
            if (!$indexExists('stok_toko', 'idx_stok_toko_composite')) {
                $table->index(['id_toko', 'id_produk'], 'idx_stok_toko_composite');
            }
        });

        Schema::table('riwayat_stok', function (Blueprint $table) use ($indexExists) {
            if (!$indexExists('riwayat_stok', 'idx_riwayat_stok_composite')) {
                $table->index(['id_produk', 'tanggal'], 'idx_riwayat_stok_composite');
            }
            if (!$indexExists('riwayat_stok', 'idx_riwayat_stok_tanggal')) {
                $table->index('tanggal', 'idx_riwayat_stok_tanggal');
            }
        });

        Schema::table('penjualan', function (Blueprint $table) use ($indexExists) {
            if (!$indexExists('penjualan', 'idx_penjualan_tanggal')) {
                $table->index('tgl_transaksi', 'idx_penjualan_tanggal');
            }
            if (!$indexExists('penjualan', 'idx_penjualan_toko')) {
                $table->index('id_toko', 'idx_penjualan_toko');
            }
        });

        Schema::table('pembelian', function (Blueprint $table) use ($indexExists) {
            if (!$indexExists('pembelian', 'idx_pembelian_tanggal')) {
                $table->index('tanggal', 'idx_pembelian_tanggal');
            }
        });

        Schema::table('retur_penjualan', function (Blueprint $table) use ($indexExists) {
            if (!$indexExists('retur_penjualan', 'idx_retur_penjualan_tanggal')) {
                $table->index('tgl_retur', 'idx_retur_penjualan_tanggal');
            }
            if (!$indexExists('retur_penjualan', 'idx_retur_penjualan_toko')) {
                $table->index('id_toko', 'idx_retur_penjualan_toko');
            }
        });

        Schema::table('retur_pembelian', function (Blueprint $table) use ($indexExists) {
            if (!$indexExists('retur_pembelian', 'idx_retur_pembelian_tanggal')) {
                $table->index('tgl_retur', 'idx_retur_pembelian_tanggal');
            }
            if (!$indexExists('retur_pembelian', 'idx_retur_pembelian_gudang')) {
                $table->index('id_gudang', 'idx_retur_pembelian_gudang');
            }
        });
    }

    public function down(): void
    {
        Schema::table('stok_gudang', function (Blueprint $table) {
            $table->dropIndex('idx_stok_gudang_composite');
        });

        Schema::table('stok_toko', function (Blueprint $table) {
            $table->dropIndex('idx_stok_toko_composite');
        });

        Schema::table('riwayat_stok', function (Blueprint $table) {
            $table->dropIndex('idx_riwayat_stok_composite');
            $table->dropIndex('idx_riwayat_stok_tanggal');
        });

        Schema::table('penjualan', function (Blueprint $table) {
            $table->dropIndex('idx_penjualan_tanggal');
            $table->dropIndex('idx_penjualan_toko');
        });

        Schema::table('pembelian', function (Blueprint $table) {
            $table->dropIndex('idx_pembelian_tanggal');
        });

        Schema::table('retur_penjualan', function (Blueprint $table) {
            $table->dropIndex('idx_retur_penjualan_tanggal');
            $table->dropIndex('idx_retur_penjualan_toko');
        });

        Schema::table('retur_pembelian', function (Blueprint $table) {
            $table->dropIndex('idx_retur_pembelian_tanggal');
            $table->dropIndex('idx_retur_pembelian_gudang');
        });
    }
};
