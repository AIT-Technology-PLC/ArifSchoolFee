<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function gdns()
    {
        return $this->hasMany(Gdn::class);
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function tenders()
    {
        return $this->hasMany(Tender::class);
    }

    public function scopeCompanyCustomers($query)
    {
        return $query->where('company_id', userCompany()->id);
    }

    public function getAll()
    {
        return $this->companyCustomers()->with(['createdBy', 'updatedBy'])->orderBy('company_name')->get();
    }

    public function getCustomerNames()
    {
        return $this->companyCustomers()->orderBy('company_name')->get(['id', 'company_name']);
    }

    public function countCustomersOfCompany()
    {
        return $this->companyCustomers()->count();
    }
}
