<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembelianDetail extends Model
{
    protected $table      = 'pembelian_detail';
    protected $primaryKey = 'id_detail_beli';
    protected $guarded    = ['id_detail_beli'];

    // Matikan timestamps karena di migrasi tabel ini tidak ada created_at/updated_at
    public $timestamps = false;

    // Casting tipe data agar mudah diolah di controller/view
    protected $casts = [
        'tgl_expired_item'  => 'date',
        'harga_beli_satuan' => 'decimal:2',
        'subtotal'          => 'decimal:2',
    ];

    /**
     * Relasi ke Header Pembelian
     */
    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'id_pembelian');
    }

    /**
     * Relasi ke Produk
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }
}
