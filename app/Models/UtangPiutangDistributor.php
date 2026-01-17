<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UtangPiutangDistributor extends Model
{
    protected $table      = 'utang_piutang_distributor';
    protected $primaryKey = 'id_utang_piutang';

    protected $fillable = [
        'id_distributor',
        'tanggal',
        'jenis_transaksi',
        'nominal',
        'keterangan',
        'no_referensi',
        'saldo_utang',
    ];

    protected $casts = [
        'tanggal'    => 'date',
        'nominal'    => 'decimal:2',
        'saldo_utang' => 'decimal:2',
    ];

    

    public function distributor()
    {
        return $this->belongsTo(Distributor::class, 'id_distributor', 'id_distributor');
    }

    

    public function scopeUtang($query)
    {
        return $query->where('jenis_transaksi', 'utang');
    }

    public function scopePembayaran($query)
    {
        return $query->where('jenis_transaksi', 'pembayaran');
    }

    

    public function scopeByDistributor($query, $id_distributor)
    {
        return $query->where('id_distributor', $id_distributor);
    }
}
