<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturPembelianDetail extends Model
{
    protected $table = 'retur_pembelian_detail';
    protected $primaryKey = 'id_retur_pembelian_detail';
    protected $fillable = [
        'id_retur_pembelian',
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

    public function returPembelian()
    {
        return $this->belongsTo(ReturPembelian::class, 'id_retur_pembelian');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }
}
