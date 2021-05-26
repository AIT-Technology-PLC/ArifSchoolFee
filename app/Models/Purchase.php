<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Purchase extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
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

    public function grns()
    {
        return $this->hasMany(Grn::class);
    }

    public function scopeCompanyPurchases($query)
    {
        return $query->where('company_id', userCompany()->id);
    }

    public function getPurchaseNoAttribute($value)
    {
        return Str::after($value, userCompany()->id . '_');
    }

    public function getTotalPurchasePriceAttribute()
    {
        $totalPrice = $this->purchaseDetails
            ->reduce(function ($carry, $item) {
                return $carry + ($item->unit_price * $item->quantity);
            }, 0);

        return number_format($totalPrice, 2);
    }

    public function getTotalPurchasePricewithVATAttribute()
    {
        $totalPrice = $this->purchaseDetails
            ->reduce(function ($carry, $item) {
                return $carry + ($item->unit_price * $item->quantity);
            }, 0);

        return number_format($totalPrice * 1.15, 2);
    }

    public function getAll()
    {
        return $this->companyPurchases()->latest()->get();
    }

    public function countPurchasesOfCompany()
    {
        return $this->companyPurchases()->count();
    }

    public function isImported()
    {
        return $this->type == 'Import';
    }
}
