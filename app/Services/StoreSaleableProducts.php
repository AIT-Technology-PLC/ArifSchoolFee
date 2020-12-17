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

        foreach ($saleDetailsData as $saleDetailData) {
            self::updateSoldQuantity($saleDetailData['product_id'], $saleDetailData);
        }
    }

    private static function updateSoldQuantity($productId, $saleDetailData)
    {
        $product = new Product();

        if ($product->isProductMerchandise($productId)) {
            $merchandise = new Merchandise();

            $merchandise = $merchandise
                ->where([
                    ['company_id', auth()->user()->employee->company_id],
                    ['product_id', $productId],
                    ['total_on_hand', '>=', $saleDetailData['quantity']],
                ])->first();

            $saleDetailData['quantity'] = $merchandise->isSoldQuantityValueValid($saleDetailData['quantity']);

            $merchandise->update([
                'total_sold' => $saleDetailData['quantity'],
            ]);

            $merchandise->decrementTotalOnHandQuantity();
        }
    }
}
