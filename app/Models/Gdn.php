<?php

namespace App\Models;

use App\Traits\Approvable;
use App\Traits\Branchable;
use App\Traits\Discountable;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use App\Traits\PricingTicket;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Gdn extends Model
{
    use MultiTenancy, SoftDeletes, Approvable, PricingTicket, Discountable, HasUserstamps, Branchable;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'issued_on' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function gdnDetails()
    {
        return $this->hasMany(GdnDetail::class);
    }

    public function reservation()
    {
        return $this->morphOne(Reservation::class, 'reservable');
    }

    public function getCodeAttribute($value)
    {
        return Str::after($value, userCompany()->id . '_');
    }

    public function getCreditPayableInPercentageAttribute()
    {
        return 100.00 - $this->cash_received_in_percentage;
    }

    public function details()
    {
        return $this->gdnDetails;
    }

    public function getAll()
    {
        if (auth()->user()->hasRole('System Manager') || auth()->user()->hasRole('Analyst')) {
            return $this->latest()->get();
        }

        return $this
            ->where('warehouse_id', auth()->user()->warehouse_id)
            ->latest()
            ->get();
    }

    public function countGdnsOfCompany()
    {
        return $this->count();
    }

    public function subtract()
    {
        $this->status = 'Subtracted From Inventory';
        $this->save();
    }

    public function isSubtracted()
    {
        return $this->status == 'Subtracted From Inventory';
    }

    public function getPaymentInCash()
    {
        if (userCompany()->isDiscountBeforeVAT()) {
            $price = $this->grandTotalPrice;
        }

        if (!userCompany()->isDiscountBeforeVAT()) {
            $price = $this->grandTotalPriceAfterDiscount;
        }

        if ($this->cash_received_in_percentage < 0) {
            return $price;
        }

        return $price * ($this->cash_received_in_percentage / 100);
    }

    public function getPaymentInCredit()
    {
        if (userCompany()->isDiscountBeforeVAT()) {
            $price = $this->grandTotalPrice;
        }

        if (!userCompany()->isDiscountBeforeVAT()) {
            $price = $this->grandTotalPriceAfterDiscount;
        }

        if ($this->credit_payable_in_percentage < 0) {
            return $price;
        }

        return $price * ($this->credit_payable_in_percentage / 100);
    }
}
