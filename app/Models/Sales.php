<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $table      = 'sales';
    protected $primaryKey = 'id_sales';
    protected $guarded    = ['id_sales'];
    public $timestamps    = false;
}
