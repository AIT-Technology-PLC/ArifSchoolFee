<?php

namespace App\Models;

use App\Traits\Approvable;
use App\Traits\Branchable;
use App\Traits\CalculateCreditPayment;
use App\Traits\Cancellable;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use App\Traits\PricingTicket;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use MultiTenancy, Branchable, HasFactory, SoftDeletes, Approvable, Cancellable, PricingTicket, HasUserstamps, CalculateCreditPayment;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'issued_on' => 'datetime',
        'expires_on' => 'datetime',
        'due_date' => 'datetime',
    ];

    public function reservedBy()
    {
        return $this->belongsTo(User::class, 'reserved_by')->withDefault(['name' => 'N/A']);
    }

    public function convertedBy()
    {
        return $this->belongsTo(User::class, 'converted_by')->withDefault(['name' => 'N/A']);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function reservable()
    {
        return $this->morphTo();
    }

    public function reservationDetails()
    {
        return $this->hasMany(ReservationDetail::class);
    }

    public function scopeConverted($query)
    {
        return $query->whereNotNull('converted_by');
    }

    public function scopeNotConverted($query)
    {
        return $query->whereNull('converted_by');
    }

    public function scopeReserved($query)
    {
        return $query->whereNotNull('reserved_by');
    }

    public function scopeNotReserved($query)
    {
        return $query->whereNull('reserved_by');
    }

    public function scopeExpired($query)
    {
        return $query->whereDate('expires_on', '<', today());
    }

    public function details()
    {
        return $this->reservationDetails;
    }

    public function convert()
    {
        $this->converted_by = authUser()->id;

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
        $this->reserved_by = authUser()->id;

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
        if (auth()->check()) {
            $this->cancelled_by = authUser()->id;
        }

        if (!auth()->check()) {
            $this->cancelled_by = $this->created_by;
        }

        $this->save();
    }

    public function canAffectInventoryValuation()
    {
        return true;
    }

    public function canReverseInventoryValuation()
    {
        return true;
    }
}