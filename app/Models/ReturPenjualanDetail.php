<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturPenjualanDetail extends Model
{
    protected $table = 'retur_penjualan_detail';
    protected $primaryKey = 'id_retur_penjualan_detail';
    protected $fillable = [
        'id_retur_penjualan',
        'id_produk',
        'qty',
        'harga_satuan',
        'subtotal',
        'alasan',
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function returPenjualan()
    {
        return $this->belongsTo(ReturPenjualan::class, 'id_retur_penjualan');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }
}
