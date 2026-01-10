<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembayaranPiutang extends Model
{
    protected $table      = 'pembayaran_piutang';
    protected $primaryKey = 'id_bayar_piutang';
    protected $guarded    = ['id_bayar_piutang'];
    public $timestamps    = false;
}
