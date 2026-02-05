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
        'tanggal_jatuh_tempo',
        'jenis_transaksi',
        'nominal',
        'keterangan',
        'no_referensi',
        'saldo_utang',
    ];

    protected $casts = [
        'tanggal'             => 'date',
        'tanggal_jatuh_tempo' => 'date',
        'nominal'             => 'decimal:2',
        'saldo_utang'         => 'decimal:2',
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

    /**
     * Scope untuk query hutang yang sudah jatuh tempo
     */
    public function scopeOverdue($query)
    {
        return $query->where('jenis_transaksi', 'utang')
                    ->whereNotNull('tanggal_jatuh_tempo')
                    ->where('tanggal_jatuh_tempo', '<', now()->toDateString());
    }

    /**
     * Accessor untuk cek apakah hutang sudah jatuh tempo
     */
    public function getIsOverdueAttribute()
    {
        if ($this->jenis_transaksi !== 'utang' || !$this->tanggal_jatuh_tempo) {
            return false;
        }
        
        return $this->tanggal_jatuh_tempo->isPast();
    }

    /**
     * Accessor untuk hitung berapa hari terlambat/tersisa
     */
    public function getDaysUntilDueAttribute()
    {
        if (!$this->tanggal_jatuh_tempo) {
            return null;
        }
        
        return now()->diffInDays($this->tanggal_jatuh_tempo, false);
    }
}
