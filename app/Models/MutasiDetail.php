<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MutasiDetail extends Model
{
    protected $table      = 'mutasi_detail';
    protected $primaryKey = 'id_mutasi_detail';
    protected $guarded    = ['id_mutasi_detail'];
    public $timestamps    = false;

    // TAMBAHAN: Relasi ke Produk
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }

    // Opsional: Jika butuh relasi balik ke Header Mutasi
    public function mutasi()
    {
        return $this->belongsTo(MutasiStok::class, 'id_mutasi');
    }
}
