<?php

namespace App\Models;

use App\Scopes\ActiveWarehouseScope;
use App\Scopes\BranchScope;
use App\Traits\TouchParentUserstamp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdjustmentDetail extends Model
{
    use HasFactory, SoftDeletes, TouchParentUserstamp;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function adjustment()
    {
        return $this->belongsTo(Adjustment::class);
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

    public function parentModel()
    {
        return $this->adjustment;
    }

    public function isSubtracted()
    {
        return $this->is_subtract == 1;
    }

    public function getByWarehouseAndProduct($warehouse, $product)
    {
        return $this->where([
            ['warehouse_id', $warehouse->id],
            ['product_id', $product->id],
        ])
            ->whereIn('adjustment_id', function ($query) {
                $query->select('id')
                    ->from('adjustments')
                    ->where('company_id', userCompany()->id)
                    ->whereNotNull('adjusted_by');
            })
            ->get()
            ->load([
                'adjustment' => function ($query) {
                    return $query->withoutGlobalScopes([BranchScope::class]);
                }]
            );
    }
}
