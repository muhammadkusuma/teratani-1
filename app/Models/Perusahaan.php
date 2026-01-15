<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    protected $table      = 'perusahaan';
    protected $primaryKey = 'id_perusahaan';

    protected $fillable = [
        'nama_perusahaan',
        'pemilik',
        'alamat',
        'kota',
        'provinsi',
        'kode_pos',
        'no_telp',
        'email',
        'website',
        'npwp',
        'logo',
    ];

    public function tokos()
    {
        return $this->hasMany(Toko::class, 'id_perusahaan', 'id_perusahaan');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'id_perusahaan', 'id_perusahaan');
    }
}
