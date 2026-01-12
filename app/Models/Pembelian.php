<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pembelian extends Model
{
    use SoftDeletes;

    protected $table      = 'pembelian';
    protected $primaryKey = 'id_pembelian';
    protected $guarded    = ['id_pembelian'];

    // Relasi ke Detail Item
    public function details()
    {
        return $this->hasMany(PembelianDetail::class, 'id_pembelian');
    }

    // Relasi ke Distributor
    public function distributor()
    {
        return $this->belongsTo(Distributor::class, 'id_distributor');
    }

    // Relasi ke Toko (Penting untuk filter data per toko)
    public function toko()
    {
        return $this->belongsTo(Toko::class, 'id_toko');
    }

    // Relasi ke User (Siapa yang input)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
