<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Distributor extends Model
{
    protected $table      = 'distributor';
    protected $primaryKey = 'id_distributor';
    protected $guarded    = ['id_distributor'];
    public $timestamps    = false;
}
