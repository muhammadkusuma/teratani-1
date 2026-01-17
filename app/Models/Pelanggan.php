<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $table      = 'pelanggan';
    protected $primaryKey = 'id_pelanggan';

    protected $fillable = [
        'id_toko',
        'kode_pelanggan',
        'nama_pelanggan',
        'no_hp',
        'alamat',
        'wilayah',
        'limit_piutang',
        'kategori_harga',
    ];

    protected $casts = [
        'limit_piutang' => 'decimal:2',
    ];

    public function toko()
    {
        return $this->belongsTo(Toko::class, 'id_toko', 'id_toko');
    }

    public function piutang()
    {
        return $this->hasMany(UtangPiutangPelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    

    public function getSaldoPiutangAttribute()
    {
        return $this->piutang()
            ->orderBy('tanggal', 'desc')
            ->orderBy('id_piutang', 'desc')
            ->first()?->saldo_piutang ?? 0;
    }

    

    public function getHargaForProduk($produk)
    {
        return match($this->kategori_harga) {
            'r1' => $produk->harga_r1 ?? $produk->harga_jual_umum,
            'r2' => $produk->harga_r2 ?? $produk->harga_jual_umum,
            'grosir' => $produk->harga_jual_grosir ?? $produk->harga_jual_umum,
            default => $produk->harga_jual_umum,
        };
    }
}
