<?php

namespace App\Utilities;

use App\Models\Product;

class Price
{
    public static function getTotalTax($totalPrice, $productId)
    {
        $product = Product::where('id', $productId)->first();

        $totalTax = number_format($totalPrice * $product->tax->amount, 2, thousands_separator:'');

        return number_format($totalTax, 2, thousands_separator:'');
    }

    public static function getTax($details)
    {
        foreach ($details as &$detail) {
            $detail['total_price'] = static::getTotalPrice(
                $detail['unit_price'],
                $detail['quantity'],
                $detail['discount'] ?? 0.00,
                $detail['product_id']
            );

            $detail['total_tax'] = static::getTotalTax(
                $detail['total_price'],
                $detail['product_id']
            );
        }

        return number_format(
            collect($details)->sum('total_tax'),
            2,
            thousands_separator:''
        );
    }

    public static function getSubtotalPrice($details)
    {
        foreach ($details as &$detail) {
            $detail['total_price'] = static::getTotalPrice($detail['unit_price'], $detail['quantity'], $detail['discount'] ?? 0.00, $detail['product_id']);
        }

        return number_format(
            collect($details)->sum('total_price'),
            2,
            thousands_separator:''
        );
    }

    public static function getTotalPrice($unitPrice, $quantity, $discount, $productId)
    {
        $product = Product::where('id', $productId)->first();

        $unitPrice = userCompany()->isPriceBeforeTax() ? $unitPrice : $unitPrice / ($product->tax->amount + 1);

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
            static::getSubtotalPrice($details) + (static::getTax($details)),
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
