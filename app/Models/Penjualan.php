<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penjualan extends Model
{
    use SoftDeletes;

    protected $table      = 'penjualan';
    protected $primaryKey = 'id_penjualan';
    protected $guarded    = ['id_penjualan'];

    protected $casts = [
        'tgl_transaksi'   => 'datetime',
        'tgl_jatuh_tempo' => 'date',
    ];

    public function details()
    {
        return $this->hasMany(PenjualanDetail::class, 'id_penjualan');
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function toko()
    {
        return $this->belongsTo(Toko::class, 'id_toko');
    }
}
