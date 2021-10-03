<?php

namespace App\Models;

use App\Traits\Branchable;
use App\Traits\Discountable;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use App\Traits\PricingTicket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use MultiTenancy, SoftDeletes, HasUserstamps, Branchable, PricingTicket, Discountable;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'sold_on' => 'datetime',
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

    public function details()
    {
        return $this->saleDetails;
    }

    public function getAll()
    {
        if (auth()->user()->hasRole('System Manager') || auth()->user()->hasRole('Analyst')) {
            return $this->latest()->get();
        }

        return $this->byBranch()->latest()->get();
    }
}
