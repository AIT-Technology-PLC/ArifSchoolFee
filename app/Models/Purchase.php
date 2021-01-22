<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'purchased_on' => 'datetime',
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

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class);
    }

    public function scopeCompanyPurchases($query)
    {
        return $query->where('company_id', auth()->user()->employee->company_id);
    }

    public function getTotalPurchasePriceAttribute()
    {
        $totalPrice = $this->purchaseDetails
            ->reduce(function ($carry, $item) {
                return $carry + ($item->unit_price * $item->quantity);
            }, 0);

        return number_format($totalPrice, 2);
    }

    public function getAll()
    {
        return $this->companyPurchases()
            ->with(['createdBy', 'updatedBy', 'company', 'purchaseDetails'])
            ->withCount('purchaseDetails')
            ->latest()
            ->get();
    }

    public function countPurchasesOfCompany()
    {
        return $this->companyPurchases()->count();
    }

    public function changeStatusToAddedToInventory()
    {
        $this->status = 'Added To Inventory';
        $this->save();
    }

    public function isAddedToInventory()
    {
        return $this->status == 'Added To Inventory';
    }

}
