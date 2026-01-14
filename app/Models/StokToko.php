<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokToko extends Model
{
    protected $table      = 'stok_toko';
    protected $primaryKey = 'id_stok';
    
    protected $fillable = [
        'id_toko',
        'id_produk',
        'stok_fisik',
        'stok_minimal',
        'lokasi_rak',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }

    public function toko()
    {
        return $this->belongsTo(Toko::class, 'id_toko');
    }
}
