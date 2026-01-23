<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturPenjualan extends Model
{
    protected $table = 'retur_penjualan';
    protected $primaryKey = 'id_retur_penjualan';
    protected $fillable = [
        'id_penjualan',
        'id_pelanggan',
        'id_toko',
        'id_user',
        'tgl_retur',
        'total_retur',
        'status_retur',
        'keterangan',
    ];

    protected $casts = [
        'tgl_retur' => 'date',
        'total_retur' => 'decimal:2',
    ];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'id_penjualan');
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan');
    }

    public function toko()
    {
        return $this->belongsTo(Toko::class, 'id_toko');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function details()
    {
        return $this->hasMany(ReturPenjualanDetail::class, 'id_retur_penjualan');
    }
}
