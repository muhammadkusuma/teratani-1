<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pembelian extends Model
{
    use SoftDeletes;
    protected $table = 'pembelian';
    protected $primaryKey = 'id_pembelian';
    protected $guarded = ['id_pembelian'];

    public function details() {
        return $this->hasMany(PembelianDetail::class, 'id_pembelian');
    }
    
    public function distributor() {
        return $this->belongsTo(Distributor::class, 'id_distributor');
    }
}