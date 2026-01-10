<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MutasiDetail extends Model
{
    protected $table      = 'mutasi_detail';
    protected $primaryKey = 'id_mutasi_detail';
    protected $guarded    = ['id_mutasi_detail'];
    public $timestamps    = false;
}
