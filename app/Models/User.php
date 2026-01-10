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

    // Hidden password
    protected $hidden = ['password'];

    public function tenants()
    {
        return $this->belongsToMany(Tenant::class, 'user_tenant_mapping', 'id_user', 'id_tenant')
            ->withPivot('role_in_tenant', 'is_primary');
    }
}
