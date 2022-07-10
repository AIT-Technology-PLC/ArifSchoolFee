<?php

namespace App\Models;

use App\Traits\Approvable;
use App\Traits\Branchable;
use App\Traits\CalculateCreditPayment;
use App\Traits\Closable;
use App\Traits\Discountable;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use App\Traits\PricingTicket;
use App\Traits\Subtractable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gdn extends Model
{
    use MultiTenancy, Branchable, SoftDeletes, Approvable, PricingTicket, Discountable, HasUserstamps, Subtractable, CalculateCreditPayment, Closable;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'issued_on' => 'datetime',
        'due_date' => 'datetime',
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

    public function credit()
    {
        return $this->hasOne(Credit::class);
    }

    public function details()
    {
        return $this->gdnDetails;
    }

    public function isConvertedToSale()
    {
        return $this->is_converted_to_sale;
    }

    public function convertToSale()
    {
        $this->is_converted_to_sale = 1;

        $this->save();
    }
}
