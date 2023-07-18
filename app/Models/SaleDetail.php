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

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
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

    public function getByWarehouseAndProduct($warehouse, $product)
    {
        return $this->where([
            ['warehouse_id', $warehouse->id],
            ['product_id', $product->id],
        ])
            ->whereIn('sale_id', function ($query) {
                $query->select('id')
                    ->from('sales')
                    ->where('company_id', userCompany()->id)
                    ->whereNotNull('subtracted_by')
                    ->whereNull('cancelled_by');
            })
            ->get()
            ->load([
                'sale' => function ($query) {
                    return $query->withoutGlobalScopes([BranchScope::class])->with(['customer']);
                }]
            );
    }
}
