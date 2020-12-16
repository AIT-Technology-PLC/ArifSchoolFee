<?php

namespace App\Services;

use App\Models\Merchandise;
use App\Models\Product;
use App\Traits\HasOptions;

class StoreSaleableProducts
{
    use HasOptions;

    private static function hasProductsMovedOut($saleStatus)
    {
        return in_array($saleStatus, (new self)->getSaleStatusForMovedProducts());
    }

    public static function storeSoldProducts($saleDetailsData, $saleStatus)
    {
        if (!self::hasProductsMovedOut($saleStatus)) {
            return true;
        }

        if (self::hasProductsMovedOut($saleStatus)) {
            foreach ($saleDetailsData as $saleDetailData) {
                self::updateSoldQuantity($saleDetailData['product_id'], $saleDetailData);
            }
        }
    }

    private static function updateSoldQuantity($productId, $saleDetailData)
    {
        $product = new Product();

        if ($product->isProductMerchandise($productId)) {
            $merchandise = new Merchandise();
            $saleDetailData['quantity'] = $merchandise->isSoldQuantityValueValid($saleDetailData['quantity']);

            $merchandise
                ->where([
                    ['company_id', auth()->user()->employee->company_id],
                    ['product_id', $productId],
                    ['total_on_hand', '>=', $saleDetailData['quantity']],
                ])
                ->update([
                    'total_sold' => $saleDetailData['quantity'],
                ]);

            $merchandise->decrementTotalOnHandQuantity();
        }
    }
}
