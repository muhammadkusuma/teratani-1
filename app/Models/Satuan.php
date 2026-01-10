<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    protected $table      = 'satuan';
    protected $primaryKey = 'id_satuan';
    protected $guarded    = ['id_satuan'];
    public $timestamps    = false;
}
