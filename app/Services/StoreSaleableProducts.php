<?php

namespace App\Services;

use App\Models\Merchandise;

class StoreSaleableProducts
{
    public static function storeSoldProducts($saleOrGdn)
    {
        $details = $saleOrGdn->saleDetails ?? $saleOrGdn->gdnDetails;

        if (!self::areProductsSaleable($details)) {
            session()->flash('message', 'Some of the products selected are not saleable products');
            return false;
        }

        if (!self::isProductSubtractNowChecked($saleOrGdn)) {
            return true;
        }

        if (!self::areProductsAvailableOnHand($details)) {
            session()->flash('message', 'Some of the products selected are not available in some or all warehouses');
            return false;
        }

        foreach ($details as $detail) {
            self::updateSoldQuantity($detail);
        }

        return true;
    }

    public static function isProductSubtractNowChecked($saleOrGdn)
    {
        if ($saleOrGdn->getTable() == 'gdns') {
            return $saleOrGdn->isGdnSubtracted();
        }

        return $saleOrGdn->isSaleSubtracted();
    }

    public static function areProductsSaleable($details)
    {
        $nonSaleableProducts = $details->filter(function ($detail) {
            return !$detail->product->isProductSaleable();
        });

        return count($nonSaleableProducts) == 0;
    }

    public static function areProductsAvailableOnHand($details)
    {
        $merchandise = new Merchandise();

        $productNotOnHand = $details->filter(
            function ($detail) use ($merchandise) {
                $isProductMerchandise = $detail->product->isProductMerchandise();
                if ($isProductMerchandise) {
                    return !$merchandise->isAvailableEnoughForSale($detail->product->id, $detail->warehouse->id, $detail->quantity);
                }
            });

        return count($productNotOnHand) == 0;
    }

    private static function updateSoldQuantity($detail)
    {
        if ($detail->product->isProductMerchandise()) {
            $quantityToSell = $detail->quantity;
            $quantityLeft = $quantityToSell;

            while ($quantityLeft) {
                $merchandise = new Merchandise();
                $merchandise = $merchandise
                    ->where([
                        ['company_id', auth()->user()->employee->company_id],
                        ['product_id', $detail->product->id],
                        ['warehouse_id', $detail->warehouse->id],
                        ['total_on_hand', '>', 0],
                    ])
                    ->oldest()
                    ->first();

                if ($merchandise->total_on_hand >= $quantityToSell) {
                    $quantityLeft = 0;
                }

                if ($quantityToSell > $merchandise->total_on_hand) {
                    $quantityLeft = $quantityToSell - $merchandise->total_on_hand;
                    $quantityToSell = $merchandise->total_on_hand;
                }

                $merchandise->update([
                    'total_sold' => $merchandise->isSoldQuantityValueValid($quantityToSell),
                ]);

                $merchandise->decrementTotalOnHandQuantity();

                $quantityToSell = $quantityLeft;
            }
        }
    }
}
