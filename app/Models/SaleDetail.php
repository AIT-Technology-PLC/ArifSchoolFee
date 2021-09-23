<?php

namespace App\Models;

use App\Traits\Discountable;
use App\Traits\PricingTicket;
use App\Traits\TouchParentUserstamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleDetail extends Model
{
    use SoftDeletes, TouchParentUserstamp, PricingTicket, Discountable;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function parentModel()
    {
        return $this->sale;
    }
}
