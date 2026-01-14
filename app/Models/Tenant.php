<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $table      = 'tenants';
    protected $primaryKey = 'id_tenant';
    public $incrementing = true; 
    protected $keyType = 'int';
    protected $guarded    = ['id_tenant'];

    public function tokos()
    {
        return $this->hasMany(Toko::class, 'id_tenant');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_tenant_mapping', 'id_tenant', 'id_user');
    }
}