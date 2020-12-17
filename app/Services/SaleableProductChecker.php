<?php

namespace App\Services;

use App\Models\Merchandise;
use App\Models\Product;
use App\Traits\HasOptions;

class SaleableProductChecker
{
    use HasOptions;

    public static function canProductsBeSold($saleDetailsData, $saleStatus)
    {
        if (!self::areProductsSaleable($saleDetailsData)) {
            session()->flash('message', 'Some of the products are not Saleable Products');
            return false;
        }

        if (!self::hasProductsMovedOut($saleStatus)) {
            return true;
        }

        if (!self::areProductsAvailableOnHand($saleDetailsData)) {
            session()->flash('message', 'Some of the products are not available on hand');
            return false;
        }

        return true;
    }

    private static function hasProductsMovedOut($saleStatus)
    {
        return in_array($saleStatus, (new self)->getSaleStatusForMovedProducts());
    }

    private static function areProductsSaleable($saleDetailsData)
    {
        $product = new Product();

        $nonSaleableProducts = array_filter($saleDetailsData,
            function ($saleDetailData) use ($product) {
                return !$product->isProductSaleable($saleDetailData['product_id']);
            });

        return count($nonSaleableProducts) == 0;
    }

    private static function areProductsAvailableOnHand($saleDetailsData)
    {
        $product = new Product();
        $merchandise = new Merchandise();

        $productNotOnHand = array_filter($saleDetailsData,
            function ($saleDetailData) use ($product, $merchandise) {

                $isProductMerchandise = $product->isProductMerchandise($saleDetailData['product_id']);
                if ($isProductMerchandise) {
                    return !$merchandise->isAvailableOnHand($saleDetailData['product_id'], $saleDetailData['quantity']);
                }

            });

        return count($productNotOnHand) == 0;
    }
}
