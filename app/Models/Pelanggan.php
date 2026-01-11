<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $table      = 'pelanggan';
    protected $primaryKey = 'id_pelanggan';
    protected $guarded    = ['id_pelanggan'];

    // Tambahkan relasi ke Toko
    public function toko()
    {
        return $this->belongsTo(Toko::class, 'id_toko');
    }

    public function penjualans()
    {
        return $this->hasMany(Penjualan::class, 'id_pelanggan');
    }

    public function kartuPiutangs()
    {
        return $this->hasMany(KartuPiutang::class, 'id_pelanggan');
    }
}
