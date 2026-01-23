<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturPembelian extends Model
{
    protected $table = 'retur_pembelian';
    protected $primaryKey = 'id_retur_pembelian';
    protected $fillable = [
        'id_pembelian',
        'id_distributor',
        'id_gudang',
        'tgl_retur',
        'total_retur',
        'keterangan',
    ];

    protected $casts = [
        'tgl_retur' => 'date',
        'total_retur' => 'decimal:2',
    ];

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'id_pembelian');
    }

    public function distributor()
    {
        return $this->belongsTo(Distributor::class, 'id_distributor');
    }

    public function gudang()
    {
        return $this->belongsTo(Gudang::class, 'id_gudang');
    }

    public function details()
    {
        return $this->hasMany(ReturPembelianDetail::class, 'id_retur_pembelian');
    }
}
