<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Distributor extends Model
{
    protected $table      = 'distributor';
    protected $primaryKey = 'id_distributor';

    protected $fillable = [
        'id_toko',
        'kode_distributor',
        'nama_distributor',
        'nama_perusahaan',
        'alamat',
        'kota',
        'provinsi',
        'kode_pos',
        'no_telp',
        'email',
        'nama_kontak',
        'no_hp_kontak',
        'npwp',
        'keterangan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function toko()
    {
        return $this->belongsTo(Toko::class, 'id_toko', 'id_toko');
    }

    public function utangPiutang()
    {
        return $this->hasMany(UtangPiutangDistributor::class, 'id_distributor', 'id_distributor');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Helper method untuk menghitung total saldo utang
    public function getSaldoUtangAttribute()
    {
        return $this->utangPiutang()
            ->orderBy('tanggal', 'desc')
            ->orderBy('id_utang_piutang', 'desc')
            ->first()?->saldo_utang ?? 0;
    }
}
