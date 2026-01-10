<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Toko extends Model
{
    protected $table      = 'toko';
    protected $primaryKey = 'id_toko';
    public $timestamps    = false;

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

    // --- PERBAIKAN: Relasi via id_tenant ---
    public function produks()
    {
        // Menghubungkan Toko ke Produk melalui id_tenant yang sama
        // Parameter: Model, Foreign Key di Produk, Local Key di Toko
        return $this->hasMany(Produk::class, 'id_tenant', 'id_tenant');
    }

    // Relasi ke StokToko (Inventory)
    public function stokToko()
    {
        return $this->hasMany(StokToko::class, 'id_toko', 'id_toko');
    }
}
