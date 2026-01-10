<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MutasiStok extends Model
{
    protected $table      = 'mutasi_stok';
    protected $primaryKey = 'id_mutasi';
    protected $guarded    = ['id_mutasi'];
    public $timestamps    = false;

    // Relasi Detail
    public function details()
    {
        return $this->hasMany(MutasiDetail::class, 'id_mutasi');
    }

    // Relasi Toko Asal
    public function tokoAsal()
    {
        return $this->belongsTo(Toko::class, 'id_toko_asal');
    }

    // Relasi Toko Tujuan
    public function tokoTujuan()
    {
        return $this->belongsTo(Toko::class, 'id_toko_tujuan');
    }

    // Relasi User Pengirim
    public function pengirim()
    {
        return $this->belongsTo(User::class, 'id_user_pengirim');
    }
}
