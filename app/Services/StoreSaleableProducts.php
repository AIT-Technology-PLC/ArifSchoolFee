<?php

namespace App\Services;

use App\Models\Merchandise;

class StoreSaleableProducts
{
    public static function storeSoldProducts($saleOrGdn)
    {
        $details = $saleOrGdn->saleDetails ?? $saleOrGdn->gdnDetails;

        if (!self::areProductsSaleable($details)) {
            return false;
        }

        if (!self::isProductSubtractNowChecked($saleOrGdn)) {
            return true;
        }

        if (!self::areProductsAvailableOnHand($details)) {
            return false;
        }

        foreach ($details as $detail) {
            self::updateSoldQuantity($detail);
        }

        return true;
    }

    public static function storeTransferredProducts($transfer)
    {
        $details = $transfer->transferDetails;

        if (!$transfer->isTransferDone()) {
            return true;
        }

        if (!self::areProductsAvailableOnHand($details)) {
            return false;
        }

        AddPurchasedItemsToInventory::addProductsPurchasedToInventory($details, $transfer->issued_on);

        foreach ($details as $detail) {
            self::updateTransferredQuantity($detail);
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

        if (count($nonSaleableProducts)) {
            session()->flash('message', 'Some of the products selected are not saleable products');
        }

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

        if (count($productNotOnHand)) {
            session()->flash('message', 'Some of the products selected are not available in some or all warehouses');
        }

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

    private static function updateTransferredQuantity($detail)
    {
        if ($detail->product->isProductMerchandise()) {
            $quantityToTransfer = $detail->quantity;
            $quantityLeft = $quantityToTransfer;

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

                if ($merchandise->total_on_hand >= $quantityToTransfer) {
                    $quantityLeft = 0;
                }

                if ($quantityToTransfer > $merchandise->total_on_hand) {
                    $quantityLeft = $quantityToTransfer - $merchandise->total_on_hand;
                    $quantityToTransfer = $merchandise->total_on_hand;
                }

                $merchandise->update([
                    'total_transfer' => $merchandise->isTransferQuantityValueValid($quantityToTransfer),
                ]);

                $merchandise->decrementTotalOnHandQuantity();

                $quantityToTransfer = $quantityLeft;
            }
        }
    }
}
