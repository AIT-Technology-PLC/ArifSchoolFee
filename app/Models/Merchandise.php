<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Merchandise extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function getTotalAttribute()
    {
        return $this->on_hand + $this->reserved;
    }

    public function scopeCompanyMerchandises($query)
    {
        return $query->where('company_id', userCompany()->id);
    }

    public function getAllOnHand()
    {
        return $this->companyMerchandises()
            ->where('on_hand', '>', 0)
            ->get();
    }

    public function getAllReserved()
    {
        return $this->companyMerchandises()
            ->where('reserved', '>', 0)
            ->get();
    }

    public function getTotalDistinctLimitedMerchandises($onHandMerchandises)
    {
        $distinctTotalLimitedMerchandises = $onHandMerchandises
            ->filter(
                function ($onHandMerchandise) {
                    return $onHandMerchandise->product->isProductLimited($onHandMerchandise->on_hand);
                }
            )
            ->groupBy('product_id')
            ->count();

        return $distinctTotalLimitedMerchandises;
    }

    public function getProductOnHandInWarehouse($onHandMerchandises, $productId, $warehouseId)
    {
        return $onHandMerchandises->where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->first()
            ->on_hand ?? 0.00;
    }

    public function getProductOnHandTotalBalance($onHandMerchandises, $productId)
    {
        return $onHandMerchandises->where('product_id', $productId)->sum('on_hand');
    }

    public function getProductReservedInWarehouse($reservedMerchandises, $productId, $warehouseId)
    {
        return $reservedMerchandises->where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->first()
            ->reserved ?? 0.00;
    }

    public function getProductReservedTotalBalance($reservedMerchandises, $productId)
    {
        return $reservedMerchandises->where('product_id', $productId)->sum('reserved');
    }
}
