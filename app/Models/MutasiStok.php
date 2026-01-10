<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MutasiStok extends Model
{
    protected $table      = 'mutasi_stok';
    protected $primaryKey = 'id_mutasi';
    protected $guarded    = ['id_mutasi'];
    public $timestamps    = false; // Karena pakai tgl_kirim manual

    public function details()
    {
        return $this->hasMany(MutasiDetail::class, 'id_mutasi');
    }
}
