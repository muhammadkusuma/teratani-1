<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendapatanPasif extends Model
{
    protected $table      = 'pendapatan_pasif';
    protected $primaryKey = 'id_pendapatan';

    protected $fillable = [
        'id_toko',
        'id_user',
        'id_penjualan',
        'kode_pendapatan',
        'tanggal_pendapatan',
        'kategori',
        'sumber',
        'jumlah',
        'metode_terima',
        'bukti_penerimaan',
        'keterangan',
        'is_otomatis',
    ];

    protected $casts = [
        'tanggal_pendapatan' => 'date',
        'jumlah' => 'decimal:2',
        'is_otomatis' => 'boolean',
    ];

    public function toko()
    {
        return $this->belongsTo(Toko::class, 'id_toko', 'id_toko');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'id_penjualan', 'id_penjualan');
    }

    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    public function scopeByDateRange($query, $from, $to)
    {
        return $query->whereBetween('tanggal_pendapatan', [$from, $to]);
    }
}
