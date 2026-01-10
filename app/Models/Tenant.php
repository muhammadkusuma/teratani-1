<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $table      = 'tenants';
    protected $primaryKey = 'id_tenant';
    
    // Karena di migrasi $table->id('id_tenant'), maka ini adalah Auto Increment
    public $incrementing = true; 
    protected $keyType = 'int';

    // Sesuaikan guarded agar create() bisa mengisi kolom lain selain id_tenant
    protected $guarded    = ['id_tenant'];

    public function tokos()
    {
        return $this->hasMany(Toko::class, 'id_tenant');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_tenant_mapping', 'id_tenant', 'id_user');
    }

    public function invoices()
    {
        return $this->hasMany(SaasInvoice::class, 'id_tenant');
    }
}