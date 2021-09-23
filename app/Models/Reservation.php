<?php

namespace App\Models;

use App\Traits\Approvable;
use App\Traits\Branchable;
use App\Traits\CalculateCreditPayment;
use App\Traits\Discountable;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use App\Traits\PricingTicket;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use MultiTenancy, HasFactory, SoftDeletes, Approvable, PricingTicket, Discountable, HasUserstamps, Branchable, CalculateCreditPayment;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'issued_on' => 'datetime',
        'expires_on' => 'datetime',
    ];

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

    public function details()
    {
        return $this->reservationDetails;
    }

    public function getAll()
    {
        if (auth()->user()->hasRole('System Manager') || auth()->user()->hasRole('Analyst')) {
            return $this->latest()->get();
        }

        return $this->byBranch()->latest()->get();
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
