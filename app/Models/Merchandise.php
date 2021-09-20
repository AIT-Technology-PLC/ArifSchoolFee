<?php

namespace App\Models;

use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Merchandise extends Model
{
    use MultiTenancy, SoftDeletes;

    protected $guarded = ['id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function getOnHandAttribute()
    {
        return number_format(
            $this->available + $this->reserved,
            2, '.', ''
        );
    }

    public function getAllOnHand()
    {
        return $this
            ->where(function ($query) {
                $query->where('merchandises.available', '>', 0)
                    ->orWhere('merchandises.reserved', '>', 0);
            })
            ->get();
    }

    public function getAllAvailable()
    {
        return $this
            ->where('available', '>', 0)
            ->get();
    }

    public function getAllReserved()
    {
        return $this
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

    public function getProductAvailableInWarehouse($availableMerchandises, $productId, $warehouseId)
    {
        return $availableMerchandises->where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->first()
            ->available ?? 0.00;
    }

    public function getProductAvailableTotalBalance($availableMerchandises, $productId)
    {
        return number_format(
            $availableMerchandises->where('product_id', $productId)->sum('available'),
            2, '.', ''
        );
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
        return number_format(
            $reservedMerchandises->where('product_id', $productId)->sum('reserved'),
            2, '.', ''
        );
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
        return number_format(
            $onHandMerchandises->where('product_id', $productId)->sum('on_hand'),
            2, '.', ''
        );
    }
}
