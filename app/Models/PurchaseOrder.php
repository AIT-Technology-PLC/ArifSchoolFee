<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'received_on' => 'datetime',
        'is_closed' => 'boolean',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function purchaseOrderDetails()
    {
        return $this->hasMany(PurchaseOrderDetail::class);
    }

    public function scopeCompanyPurchaseOrder($query)
    {
        return $query->where('company_id', auth()->user()->employee->company_id);
    }

    public function getCodeAttribute($value)
    {
        return Str::after($value, auth()->user()->employee->company->id . '_');
    }

    public function getAll()
    {
        return $this->companyPurchaseOrder()->withCount('purchaseOrderDetails')->latest()->get();
    }

    public function countPurchaseOrdersOfCompany()
    {
        return $this->companyPurchaseOrder()->count();
    }

    public function changeStatusToClose()
    {
        $this->status = 1;
        $this->save();
    }

    public function isPurchaseOrderClosed()
    {
        return $this->is_closed;
    }
}
