<?php

namespace App\Models;

use App\Traits\Approvable;
use App\Traits\Branchable;
use App\Traits\CalculateCreditPayment;
use App\Traits\Discountable;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use App\Traits\PricingTicket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use MultiTenancy, Branchable, SoftDeletes, HasUserstamps, PricingTicket, Discountable, Approvable, CalculateCreditPayment;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'issued_on' => 'datetime',
        'due_date' => 'datetime',
    ];

    public function cancelledBy()
    {
        return $this->belongsTo(User::class, 'cancelled_by')->withDefault(['name' => 'N/A']);
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

    public function details()
    {
        return $this->saleDetails;
    }

    public function scopeCancelled($query)
    {
        return $query->whereNotNull('cancelled_by');
    }

    public function scopeNotCancelled($query)
    {
        return $query->whereNull('cancelled_by');
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
