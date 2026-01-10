<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table      = 'produk';
    protected $primaryKey = 'id_produk';
    protected $guarded    = ['id_produk'];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    public function satuanKecil()
    {
        return $this->belongsTo(Satuan::class, 'id_satuan_kecil');
    }

    public function satuanBesar()
    {
        return $this->belongsTo(Satuan::class, 'id_satuan_besar');
    }

    public function stokTokos()
    {
        return $this->hasMany(StokToko::class, 'id_produk');
    }

    public function stokBatches()
    {
        return $this->hasMany(StokBatch::class, 'id_produk');
    }

    // Tambahkan method ini di dalam class Produk
    public function stokToko()
    {
        // Relasi ke tabel stok_toko
        // Parameter: Model, foreign_key, local_key
        // Sesuaikan 'id_produk' dengan kolom yang ada di migrasi dan model Produk
        return $this->hasOne(StokToko::class, 'id_produk', 'id_produk');
        // Atau jika Anda ingin mengambil semua stok di semua toko: hasMany
    }
}
