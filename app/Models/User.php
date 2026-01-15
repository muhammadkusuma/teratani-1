<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    
    protected $table      = 'users';
    protected $primaryKey = 'id_user';
    protected $guarded    = ['id_user'];
    protected $hidden = ['password'];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan', 'id_karyawan');
    }

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan', 'id_perusahaan');
    }

    public function getJabatanAttribute()
    {
        if ($this->is_superadmin) {
            return 'Superadmin';
        }

        return $this->karyawan?->jabatan ?? 'Unknown';
    }
}
