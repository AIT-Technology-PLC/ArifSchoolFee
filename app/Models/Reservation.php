<?php

namespace App\Models;

use App\Traits\Approvable;
use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Reservation extends Model
{
    use HasFactory, SoftDeletes, Approvable;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'issued_on' => 'datetime',
        'expires_on' => 'datetime',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function reservedBy()
    {
        return $this->belongsTo(User::class, 'reserved_by');
    }

    public function cancelledBy()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    public function convertedBy()
    {
        return $this->belongsTo(User::class, 'converted_by');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function reservable()
    {
        return $this->morphTo();
    }

    public function reservationDetails()
    {
        return $this->hasMany(ReservationDetail::class);
    }

    public function scopeCompanyReservation($query)
    {
        return $query->where('company_id', userCompany()->id);
    }

    public function getCodeAttribute($value)
    {
        return Str::after($value, userCompany()->id . '_');
    }

    public function getTotalPriceAttribute()
    {
        $totalPrice = $this->reservationDetails
            ->reduce(function ($carry, $item) {
                return $carry + ($item->unit_price * $item->quantity);
            }, 0);

        return $totalPrice;
    }

    public function getTotalPriceWithVATAttribute()
    {
        return $this->totalPrice * 1.15;
    }

    public function getCreditPayableInPercentageAttribute()
    {
        return 100.00 - $this->cash_received_in_percentage;
    }

    public function getPaymentInCash()
    {
        if ($this->cash_received_in_percentage < 0) {
            return $this->totalPriceWithVAT;
        }

        return $this->totalPriceWithVAT * ($this->cash_received_in_percentage / 100);
    }

    public function getPaymentInCredit()
    {
        if ($this->credit_payable_in_percentage < 0) {
            return $this->totalPriceWithVAT;
        }

        return $this->totalPriceWithVAT * ($this->credit_payable_in_percentage / 100);
    }

    public function convert()
    {
        $this->converted_by = auth()->id();

        $this->save();
    }

    public function isConverted()
    {
        if (is_null($this->converted_by)) {
            return false;
        }

        return true;
    }

    public function reserve()
    {
        $this->reserved_by = auth()->id();

        $this->save();
    }

    public function isReserved()
    {
        if (is_null($this->reserved_by)) {
            return false;
        }

        return true;
    }

    public function cancel()
    {
        $this->cancelled_by = auth()->id();

        $this->save();
    }

    public function isCancelled()
    {
        if (is_null($this->cancelled_by)) {
            return false;
        }

        return true;
    }
}
