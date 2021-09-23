<?php

namespace App\Models;

use App\Traits\Discountable;
use App\Traits\PricingProduct;
use App\Traits\TouchParentUserstamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseDetail extends Model
{
    use SoftDeletes, TouchParentUserstamp, PricingProduct, Discountable;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function parentModel()
    {
        return $this->purchase;
    }
}
