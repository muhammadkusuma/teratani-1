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
}
