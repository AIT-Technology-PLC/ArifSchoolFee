<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
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

    public function getAll()
    {
        return $this->with(['createdBy', 'updatedBy'])->where('company_id', auth()->user()->employee->company_id)->get();
    }

    public function getSupplierNames()
    {
        return $this->where('company_id', auth()->user()->employee->company_id)->get(['id', 'company_name']);
    }

    public function countSuppliersOfCompany()
    {
        return $this->where('company_id', auth()->user()->employee->company_id)->count();
    }
}
