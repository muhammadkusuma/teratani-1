<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    protected $table      = 'pengeluaran';
    protected $primaryKey = 'id_pengeluaran';

    protected $fillable = [
        'id_toko',
        'id_user',
        'kode_pengeluaran',
        'tanggal_pengeluaran',
        'kategori',
        'deskripsi',
        'jumlah',
        'metode_bayar',
        'bukti_pembayaran',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_pengeluaran' => 'date',
        'jumlah' => 'decimal:2',
    ];

    public function toko()
    {
        return $this->belongsTo(Toko::class, 'id_toko', 'id_toko');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    public function scopeByDateRange($query, $from, $to)
    {
        return $query->whereBetween('tanggal_pengeluaran', [$from, $to]);
    }
}
