<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KartuPiutang extends Model
{
    protected $table      = 'kartu_piutang';
    protected $primaryKey = 'id_piutang';
    protected $guarded    = ['id_piutang'];

    public function pembayarans()
    {
        return $this->hasMany(PembayaranPiutang::class, 'id_piutang');
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan');
    }

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'id_penjualan');
    }
}
