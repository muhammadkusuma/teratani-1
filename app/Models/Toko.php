<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Toko extends Model
{
    protected $table      = 'toko';
    protected $primaryKey = 'id_toko';
    protected $guarded    = ['id_toko'];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'id_tenant');
    }
}
