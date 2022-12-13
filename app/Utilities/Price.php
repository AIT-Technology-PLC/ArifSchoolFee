<?php

namespace App\Utilities;

use App\Models\Product;

class Price
{
    public static function getTotalVat($totalPrice, $productID)
    {
        $product = Product::where('id', $productID)->first();

        $totalVat = number_format($totalPrice * $product->tax->amount, 2, thousands_separator:'');

        return number_format($totalVat, 2, thousands_separator:'');
    }

    public static function getVat($details)
    {
        foreach ($details as &$detail) {
            $detail['total_vat'] = static::getTotalVat(static::getTotalPrice($detail['unit_price'], $detail['quantity'], $detail['discount'], $detail['product_id'] ?? 0.00), $detail['product_id']);
        }

        return number_format(
            collect($details)->sum('total_vat'),
            2,
            thousands_separator:''
        );
    }

    public static function getSubtotalPrice($details)
    {
        foreach ($details as &$detail) {
            $detail['total_price'] = static::getTotalPrice($detail['unit_price'], $detail['quantity'], $detail['discount'], $detail['product_id'] ?? 0.00);
        }

        return number_format(
            collect($details)->sum('total_price'),
            2,
            thousands_separator:''
        );
    }

    public static function getTotalPrice($unitPrice, $quantity, $discount, $productID)
    {
        $product = Product::where('id', $productID)->first();
        $unitPrice = userCompany()->isPriceBeforeVAT() ? $unitPrice : $unitPrice / ($product->tax->amount + 1);
        $totalPrice = number_format($unitPrice * $quantity, 2, thousands_separator:'');
        $totalPrice = number_format($totalPrice-static::getDiscountAmount($discount, $totalPrice), 2, thousands_separator:'');

        return $totalPrice;
    }

    public static function getDiscountAmount($discount, $price)
    {
        $discount = ($discount ?? 0.00) / 100;

        return number_format($price * $discount, 2, thousands_separator:'');
    }

    public static function getGrandTotalPrice($details)
    {
        return number_format(
            static::getSubtotalPrice($details) + (static::getVat($details)),
            2,
            thousands_separator:''
        );
    }

    public static function getGrandTotalPriceAfterDiscount($discount, $details)
    {
        return number_format(
            static::getGrandTotalPrice($details)-static::getDiscountAmount($discount, static::getGrandTotalPrice($details)),
            2,
            thousands_separator:''
        );
    }
}
