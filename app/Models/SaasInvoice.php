<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaasInvoice extends Model
{
    protected $table      = 'saas_invoices';
    protected $primaryKey = 'id_invoice';
    protected $guarded    = ['id_invoice'];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'id_tenant');
    }
}
