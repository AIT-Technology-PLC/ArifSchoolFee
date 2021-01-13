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

    public function sivs()
    {
        return $this->morphMany(Siv::class, 'sivable');
    }

    public function getReceiptNoAttribute($value)
    {
        return Str::after($value, auth()->user()->employee->company->id . '_');
    }

    public function scopeCompanySales($query)
    {
        return $query->where('company_id', auth()->user()->employee->company_id);
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
        return $this->companySales()
            ->with(['createdBy', 'updatedBy', 'company', 'saleDetails'])
            ->withCount('saleDetails')
            ->latest()
            ->get();
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
}
