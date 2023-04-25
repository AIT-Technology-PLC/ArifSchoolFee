<?php

namespace App\Models;

use App\Traits\PricingProduct;
use App\Traits\TouchParentUserstamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleDetail extends Model
{
    use SoftDeletes, TouchParentUserstamp, PricingProduct;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $appends = [
        'originalUnitPrice',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function merchandiseBatch()
    {
        return $this->belongsTo(MerchandiseBatch::class);
    }

    public function parentModel()
    {
        return $this->sale;
    }

    public function getWithheldAmountAttribute()
    {
        if (!$this->sale->hasWithholding()) {
            return 0;
        }

        if ($this->totalPrice < userCompany()->withholdingTaxes['rules'][$this->product->type]) {
            return 0;
        }

        return $this->totalPrice * userCompany()->withholdingTaxes['tax_rate'];
    }
}
