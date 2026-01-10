<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Toko extends Model
{
    protected $table      = 'toko';
    protected $primaryKey = 'id_toko';

    // --- PERBAIKAN: Matikan timestamps ---
    public $timestamps    = false; 
    // -------------------------------------

    protected $fillable = [
        'id_tenant',
        'kode_toko',
        'nama_toko',
        'alamat',
        'kota',
        'no_telp',
        'is_pusat',
        'is_active',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'id_tenant', 'id_tenant');
    }
}