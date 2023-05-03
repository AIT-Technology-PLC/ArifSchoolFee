<?php

namespace App\Models;

use App\Traits\Approvable;
use App\Traits\Branchable;
use App\Traits\CalculateCreditPayment;
use App\Traits\Cancellable;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use App\Traits\PricingTicket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use MultiTenancy, Branchable, SoftDeletes, HasUserstamps, PricingTicket, Approvable, Cancellable, CalculateCreditPayment;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'issued_on' => 'datetime',
        'due_date' => 'datetime',
        'has_withholding' => 'boolean',
    ];

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

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function credit()
    {
        return $this->morphOne(Credit::class, 'creditable');
    }

    public function details()
    {
        return $this->saleDetails;
    }

    public function hasWithholding()
    {
        return $this->has_withholding;
    }

    public function getTotalWithheldAmountAttribute()
    {
        if (!$this->hasWithholding()) {
            return 0;
        }

        return number_format(
            $this->saleDetails->sum->withheldAmount,
            2,
            thousands_separator:''
        );
    }
}
