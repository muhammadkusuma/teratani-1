<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Toko extends Model
{
    protected $table      = 'toko';
    protected $primaryKey = 'id_toko';

    protected $fillable = [
        'id_perusahaan',
        'kode_toko',
        'nama_toko',
        'alamat',
        'kota',
        'no_telp',
        'info_rekening',
        'is_pusat',
        'is_active',
    ];

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan', 'id_perusahaan');
    }

    public function produks()
    {
        return $this->hasMany(Produk::class, 'id_toko', 'id_toko');
    }

    public function stokToko()
    {
        return $this->hasMany(StokToko::class, 'id_toko', 'id_toko');
    }

    public function distributors()
    {
        return $this->hasMany(Distributor::class, 'id_toko', 'id_toko');
    }

    public function karyawans()
    {
        return $this->hasMany(Karyawan::class, 'id_toko', 'id_toko');
    }

    public function pengeluarans()
    {
        return $this->hasMany(Pengeluaran::class, 'id_toko', 'id_toko');
    }
}
