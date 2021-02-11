<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['company_id', 'created_by', 'updated_by', 'company_name', 'contact_name', 'email', 'phone', 'country'];

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

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class);
    }

    public function grns()
    {
        return $this->hasMany(Grn::class);
    }

    public function scopeCompanySuppliers($query)
    {
        return $query->where('company_id', auth()->user()->employee->company_id);
    }

    public function getAll()
    {
        return $this->companySuppliers()->with(['createdBy', 'updatedBy'])->orderBy('company_name')->get();
    }

    public function getSupplierNames()
    {
        return $this->companySuppliers()->orderBy('company_name')->get(['id', 'company_name']);
    }

    public function countSuppliersOfCompany()
    {
        return $this->companySuppliers()->count();
    }
}
