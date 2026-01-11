<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengeluaran extends Model
{
    use SoftDeletes;
    protected $table      = 'pengeluaran';
    protected $primaryKey = 'id_pengeluaran';
    // Sesuai field di migration
    protected $fillable   = [
        'id_toko', 'id_user', 'no_referensi', 
        'tgl_pengeluaran', 'kategori_biaya', 
        'nominal', 'keterangan', 'bukti_foto'
    ];

    public function toko()
    {
        return $this->belongsTo(Toko::class, 'id_toko');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}