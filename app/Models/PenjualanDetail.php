<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenjualanDetail extends Model
{
    protected $table      = 'penjualan_detail';
    protected $primaryKey = 'id_detail';
    protected $guarded    = ['id_detail'];
    public $timestamps    = false;

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }
}
