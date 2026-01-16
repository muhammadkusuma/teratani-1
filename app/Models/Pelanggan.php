<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $table      = 'pelanggan';
    protected $primaryKey = 'id_pelanggan';

    protected $fillable = [
        'id_toko',
        'kode_pelanggan',
        'nama_pelanggan',
        'no_hp',
        'alamat',
        'wilayah',
        'limit_piutang',
    ];

    public function toko()
    {
        return $this->belongsTo(Toko::class, 'id_toko', 'id_toko');
    }

    public function piutang()
    {
        return $this->hasMany(UtangPiutangPelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    // Helper method untuk menghitung total saldo piutang
    public function getSaldoPiutangAttribute()
    {
        return $this->piutang()
            ->orderBy('tanggal', 'desc')
            ->orderBy('id_piutang', 'desc')
            ->first()?->saldo_piutang ?? 0;
    }
}
