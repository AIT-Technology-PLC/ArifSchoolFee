<?php

namespace App\Services;

use App\Models\Merchandise;

class StoreSaleableProducts
{
    public static function storeSoldProducts($sale)
    {
        if (!self::areProductsSaleable($sale->saleDetails)) {
            session()->flash('message', 'Some of the products are not Saleable Products');
            return false;
        }

        if (!self::isProductSubtractNowChecked($sale->status)) {
            return true;
        }

        if (!self::areProductsAvailableOnHand($sale->saleDetails)) {
            session()->flash('message', 'Some of the products are not available on hand');
            return false;
        }

        foreach ($sale->saleDetails as $saleDetail) {
            self::updateSoldQuantity($saleDetail);
        }

        return true;
    }

    private static function isProductSubtractNowChecked($saleStatus)
    {
        return $saleStatus == "Subtracted From Inventory";
    }

    private static function areProductsSaleable($saleDetails)
    {
        $nonSaleableProducts = $saleDetails->filter(function ($saleDetail) {
            return !$saleDetail->product->isProductSaleable();
        });

        return count($nonSaleableProducts) == 0;
    }

    private static function areProductsAvailableOnHand($saleDetails)
    {
        $merchandise = new Merchandise();

        $productNotOnHand = $saleDetails->filter(
            function ($saleDetail) use ($merchandise) {
                $isProductMerchandise = $saleDetail->product->isProductMerchandise();
                if ($isProductMerchandise) {
                    return !$merchandise->isAvailableOnHand($saleDetail->product->id, $saleDetail->quantity);
                }
            });

        return count($productNotOnHand) == 0;
    }

    private static function updateSoldQuantity($saleDetail)
    {
        if ($saleDetail->product->isProductMerchandise()) {
            $merchandise = new Merchandise();

            $merchandise = $merchandise
                ->where([
                    ['company_id', auth()->user()->employee->company_id],
                    ['product_id', $saleDetail->product->id],
                    ['total_on_hand', '>=', $saleDetail->quantity],
                ])
                ->first();

            $saleDetail->quantity = $merchandise->isSoldQuantityValueValid($saleDetail->quantity);

            $merchandise->update([
                'total_sold' => $saleDetail->quantity,
            ]);

            $merchandise->decrementTotalOnHandQuantity();
        }
    }
}
