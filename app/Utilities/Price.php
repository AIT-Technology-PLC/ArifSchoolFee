<?php

namespace App\Utilities;

class Price
{
    public static function getVat($details)
    {
        return number_format(
            static::getSubtotalPrice($details) * 0.15,
            2,
            thousands_separator:''
        );
    }

    public static function getSubtotalPrice($details)
    {
        foreach ($details as &$detail) {
            $detail['total_price'] = static::getTotalPrice($detail['unit_price'], $detail['quantity'], $detail['discount'] ?? 0.00);
        }

        return number_format(
            collect($details)->sum('total_price'),
            2,
            thousands_separator:''
        );
    }

    public static function getTotalPrice($unitPrice, $quantity, $discount)
    {
        $unitPrice = userCompany()->isPriceBeforeVAT() ? $unitPrice : $unitPrice / 1.15;
        $totalPrice = number_format($unitPrice * $quantity, 2, thousands_separator:'');
        $totalPrice = number_format($totalPrice - static::getDiscountAmount($discount, $totalPrice), 2, thousands_separator:'');

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
            static::getGrandTotalPrice($details) - static::getDiscountAmount($discount, static::getGrandTotalPrice($details)),
            2,
            thousands_separator:''
        );
    }
}
