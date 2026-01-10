<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokToko extends Model
{
    protected $table      = 'stok_toko';
    protected $primaryKey = 'id_stok';
    protected $guarded    = ['id_stok'];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }

    public function toko()
    {
        return $this->belongsTo(Toko::class, 'id_toko');
    }
}
