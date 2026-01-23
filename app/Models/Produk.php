<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table      = 'produk';
    protected $primaryKey = 'id_produk';

    protected $fillable = [
        'sku',
        'barcode',
        'nama_produk',
        'id_kategori',
        'id_satuan_kecil',
        'id_satuan_besar',
        'nilai_konversi',
        'harga_beli',
        'harga_jual_umum',
        'harga_jual_grosir',
        'harga_r1',
        'harga_r2',
        'gambar_produk',
        'is_active',
        'stok_minimum',
    ];

    protected $casts = [
        'harga_beli'        => 'decimal:2',
        'harga_jual_umum'   => 'decimal:2',
        'harga_jual_grosir' => 'decimal:2',
        'harga_r1'          => 'decimal:2',
        'harga_r2'          => 'decimal:2',
        'is_active'         => 'boolean',
        'stok_minimum'      => 'integer',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    public function satuanKecil()
    {
        return $this->belongsTo(Satuan::class, 'id_satuan_kecil', 'id_satuan');
    }

    public function satuanBesar()
    {
        return $this->belongsTo(Satuan::class, 'id_satuan_besar', 'id_satuan');
    }

    public function stokToko()
    {
        return $this->hasOne(StokToko::class, 'id_produk', 'id_produk');
    }

    public function stokTokos()
    {
        return $this->hasMany(StokToko::class, 'id_produk', 'id_produk');
    }
}
