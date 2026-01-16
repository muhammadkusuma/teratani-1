<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UtangPiutangPelanggan extends Model
{
    protected $table      = 'utang_piutang_pelanggan';
    protected $primaryKey = 'id_piutang';

    protected $fillable = [
        'id_pelanggan',
        'tanggal',
        'jenis_transaksi',
        'nominal',
        'keterangan',
        'no_referensi',
        'saldo_piutang',
    ];

    protected $casts = [
        'tanggal'       => 'date',
        'nominal'       => 'decimal:2',
        'saldo_piutang' => 'decimal:2',
    ];

    // Relasi ke Pelanggan
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    // Scope untuk filter jenis transaksi
    public function scopePiutang($query)
    {
        return $query->where('jenis_transaksi', 'piutang');
    }

    public function scopePembayaran($query)
    {
        return $query->where('jenis_transaksi', 'pembayaran');
    }

    // Scope untuk filter by pelanggan
    public function scopeByPelanggan($query, $id_pelanggan)
    {
        return $query->where('id_pelanggan', $id_pelanggan);
    }
}
