<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Toko extends Model
{
    protected $table      = 'toko';
    protected $primaryKey = 'id_toko';

    protected $fillable = [
        'kode_toko',
        'nama_toko',
        'alamat',
        'kota',
        'no_telp',
        'info_rekening',
        'is_pusat',
        'is_active',
    ];

    public function produks()
    {
        return $this->hasMany(Produk::class, 'id_toko', 'id_toko');
    }

    public function stokToko()
    {
        return $this->hasMany(StokToko::class, 'id_toko', 'id_toko');
    }
}
