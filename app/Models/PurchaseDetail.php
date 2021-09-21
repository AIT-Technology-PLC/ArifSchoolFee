<?php

namespace App\Models;

use App\Traits\TouchParentUserstamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseDetail extends Model
{
    use SoftDeletes, TouchParentUserstamp;

    protected $fillable = ['purchase_id', 'product_id', 'quantity', 'unit_price'];

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
