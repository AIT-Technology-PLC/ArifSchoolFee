<?php

namespace App\Models;

use App\Traits\PricingProduct;
use App\Traits\TouchParentUserstamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrderDetail extends Model
{
    use SoftDeletes, TouchParentUserstamp, PricingProduct;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public static function booted()
    {
        static::creating(fn($model) => $model->quantity_left = $model->quantity);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function parentModel()
    {
        return $this->purchaseOrder;
    }
}
