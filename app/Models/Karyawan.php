<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Karyawan extends Model
{
    protected $table      = 'karyawan';
    protected $primaryKey = 'id_karyawan';

    protected $fillable = [
        'id_toko',
        'kode_karyawan',
        'nik',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'no_hp',
        'email',
        'jabatan',
        'tanggal_masuk',
        'tanggal_keluar',
        'status_karyawan',
        'gaji_pokok',
        'foto',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_lahir'  => 'date',
        'tanggal_masuk'  => 'date',
        'tanggal_keluar' => 'date',
        'gaji_pokok'     => 'decimal:2',
    ];

    public function toko()
    {
        return $this->belongsTo(Toko::class, 'id_toko', 'id_toko');
    }

    public function scopeActive($query)
    {
        return $query->where('status_karyawan', 'Aktif');
    }

    public function scopeByJabatan($query, $jabatan)
    {
        return $query->where('jabatan', $jabatan);
    }

    public function getUmurAttribute()
    {
        if (!$this->tanggal_lahir) {
            return null;
        }
        return Carbon::parse($this->tanggal_lahir)->age;
    }

    public function getMasaKerjaAttribute()
    {
        if (!$this->tanggal_masuk) {
            return null;
        }
        
        $endDate = $this->tanggal_keluar ?? now();
        $diff = Carbon::parse($this->tanggal_masuk)->diff($endDate);
        
        $years = $diff->y;
        $months = $diff->m;
        
        if ($years > 0 && $months > 0) {
            return "{$years} tahun {$months} bulan";
        } elseif ($years > 0) {
            return "{$years} tahun";
        } elseif ($months > 0) {
            return "{$months} bulan";
        } else {
            return "Kurang dari 1 bulan";
        }
    }
}
