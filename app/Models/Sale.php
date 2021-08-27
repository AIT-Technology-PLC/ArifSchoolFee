<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Sale extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'sold_on' => 'datetime',
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

        return Str::after($value, userCompany()->id . '_');
    }

    public function scopeCompanySales($query)
    {
        return $query->where('company_id', userCompany()->id);
    }

    public function getTotalSalePriceAttribute()
    {
        $totalPrice = $this->saleDetails
            ->reduce(function ($carry, $item) {
                return $carry + ($item->unit_price * $item->quantity);
            }, 0);

        return number_format($totalPrice, 2);
    }

    public function getTotalSalePriceWithVATAttribute()
    {
        $totalPrice = $this->saleDetails
            ->reduce(function ($carry, $item) {
                return $carry + ($item->unit_price * $item->quantity);
            }, 0);

        return number_format($totalPrice * 1.15, 2);
    }

    public function getAll()
    {
        if (auth()->user()->hasRole('System Manager') || auth()->user()->hasRole('Analyst')) {
            return $this->companySales()->latest()->get();
        }

        return $this->companySales()
            ->where('warehouse_id', auth()->user()->warehouse_id)
            ->latest()
            ->get();
    }

    public function countSalesOfCompany()
    {
        return $this->companySales()->count();
    }

    public function isSalePaymentCash()
    {
        return $this->payment_type == "Cash";
    }
}
