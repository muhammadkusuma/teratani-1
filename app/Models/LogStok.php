<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogStok extends Model
{
    protected $table      = 'log_stok';
    protected $primaryKey = 'id_log';
    protected $guarded    = ['id_log'];

    // Matikan updated_at karena log sifatnya immutable (sekali tulis tidak boleh edit)
    const UPDATED_AT = null;
}
