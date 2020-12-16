<?php

namespace App\Services;

use App\Models\Merchandise;
use App\Models\Product;

class SaleableProductChecker
{
    public static function canProductsBeSold($saleDetailsData)
    {
        if (!self::areProductsSaleable($saleDetailsData)) {
            return "Some of the Products are not Saleable Products";
        }

        if (!self::areProductsAvailableOnHand($saleDetailsData)) {
            return "Some of the Products are not available on hand";
        }

        return true;
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
