<?php

namespace App\Models;

use App\Traits\MultiTenancy;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use MultiTenancy, SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

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

    public function getAll()
    {
        return $this->with(['createdBy', 'updatedBy'])->orderBy('company_name')->get();
    }

    public function getSupplierNames()
    {
        return $this->orderBy('company_name')->get(['id', 'company_name']);
    }

    public function countSuppliersOfCompany()
    {
        return $this->count();
    }
}
