<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Sale extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'sold_on' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'is_manual' => 'boolean',
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

    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function gdns()
    {
        return $this->hasMany(Gdn::class);
    }

    public function getReceiptNoAttribute($value)
    {
        if (Str::endsWith($value, '_') || !$value) {
            return null;
        }

        return Str::after($value, auth()->user()->employee->company->id . '_');
    }

    public function scopeCompanySales($query)
    {
        return $query->where('company_id', auth()->user()->employee->company_id);
    }

    public function scopeWhereManual($query, $value)
    {
        return $query->where('is_manual', $value);
    }

    public function getTotalSalePriceAttribute()
    {
        $totalPrice = $this->saleDetails
            ->reduce(function ($carry, $item) {
                return $carry + ($item->unit_price * $item->quantity);
            }, 0);

        return number_format($totalPrice, 2);
    }

    public function getAll()
    {
        return $this->companySales()->withCount('saleDetails')->latest()->get();
    }

    public function getManualSales()
    {
        return $this->companySales()->whereManual(1)->latest()->get();
    }

    public function getAutomatedSales()
    {
        return $this->companySales()->whereManual(0)->latest()->get();
    }

    public function countSalesOfCompany()
    {
        return $this->companySales()->count();
    }

    public function changeStatusToSubtractedFromInventory()
    {
        $this->status = 'Subtracted From Inventory';
        $this->save();
    }

    public function isSaleSubtracted()
    {
        return $this->status == 'Subtracted From Inventory';
    }

    public function isSaleManual()
    {
        return $this->is_manual;
    }
}
