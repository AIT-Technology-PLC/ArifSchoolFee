<?php

namespace App\Models;

use App\Models\Exchange;
use App\Models\MerchandiseBatch;
use App\Models\Product;
use App\Models\Warehouse;
use App\Scopes\ActiveWarehouseScope;
use App\Traits\PricingProduct;
use App\Traits\TouchParentUserstamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExchangeDetail extends Model
{
    use SoftDeletes, PricingProduct, TouchParentUserstamp;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $appends = [
        'originalUnitPrice',
    ];

    public function exchange()
    {
        return $this->belongsTo(Exchange::class);
    }

    public function exchangeDetailable()
    {
        return $this->morphTo();
    }

    public function parentModel()
    {
        return $this->exchange;
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class)->withoutGlobalScopes([ActiveWarehouseScope::class]);
    }

    public function merchandiseBatch()
    {
        return $this->belongsTo(MerchandiseBatch::class);
    }
}
