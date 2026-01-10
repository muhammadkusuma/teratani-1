<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokBatch extends Model
{
    protected $table      = 'stok_batch';
    protected $primaryKey = 'id_batch';
    protected $guarded    = ['id_batch'];

    protected $casts = ['tgl_expired' => 'date', 'tgl_masuk' => 'date'];
}
