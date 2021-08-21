<?php

namespace App\Models;

use App\Traits\Discountable;
use App\Traits\PricingProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GdnDetail extends Model
{
    use SoftDeletes, PricingProduct, Discountable;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function gdn()
    {
        return $this->belongsTo(Gdn::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function getByWarehouseAndProduct($warehouse, $product)
    {
        return $this->where([
            ['warehouse_id', $warehouse->id],
            ['product_id', $product->id],
        ])
            ->whereIn('gdn_id', function ($query) {
                $query->select('id')
                    ->from('gdns')
                    ->where([
                        ['company_id', userCompany()->id],
                        ['status', 'Subtracted From Inventory'],
                    ]);
            })
            ->get()
            ->load(['gdn.customer', 'product']);
    }
}
