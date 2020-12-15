<?php

namespace App\Services;

use App\Models\Merchandise;
use App\Models\Product;

class SaleableProductChecker
{
    public static function canProductsBeSold($saleDetailsData)
    {
        if (!self::areProductsSaleable($saleDetailsData)) {
            return false;
        }

        return self::areProductsAvailableOnHand($saleDetailsData);
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
        return self::areMerchandiseProductsAvailableOnHand($saleDetailsData);
    }

    private static function areMerchandiseProductsAvailableOnHand($saleDetailsData)
    {
        $product = new Product();
        $merchandise = new Merchandise();

        $merchandiseNotOnHand = array_filter($saleDetailsData,
            function ($saleDetailData) use ($product, $merchandise) {
                $isProductMerchandise = $product->isProductMerchandise($saleDetailData['product_id']);
                if ($isProductMerchandise) {
                    return !$merchandise->isAvailableOnHand($saleDetailData['product_id'], $saleDetailData['quantity']);
                }
            });

        return count($merchandiseNotOnHand) == 0;
    }
}
