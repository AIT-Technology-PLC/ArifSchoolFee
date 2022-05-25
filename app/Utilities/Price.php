<?php

namespace App\Utilities;

class Price
{
    public static function getVat($details)
    {
        return number_format(
            static::getSubTotalPrice($details) * 0.15,
            2,
            thousands_separator:''
        );
    }

    public static function getSubtotalPrice($details)
    {
        foreach ($details as &$detail) {
            $detail['unit_price'] = userCompany()->isPriceBeforeVAT() ? $detail['unit_price'] : $detail['unit_price'] / 1.15;
            $totalPrice = number_format($detail['unit_price'] * $detail['quantity'], 2, thousands_separator:'');
            $discountAmount = 0.00;

            if (userCompany()->isDiscountBeforeVAT()) {
                $discount = ($detail['discount'] ?? 0.00) / 100;
                $discountAmount = number_format($totalPrice * $discount, 2, thousands_separator:'');
            }

            $detail['total_price'] = number_format($totalPrice - $discountAmount, 2, thousands_separator:'');
        }

        return number_format(
            collect($details)->sum('total_price'),
            2,
            thousands_separator:''
        );
    }

    public static function getTotalPrice($details)
    {
        foreach ($details as &$detail) {
            $detail['unit_price'] = userCompany()->isPriceBeforeVAT() ? $detail['unit_price'] : $detail['unit_price'] / 1.15;
            $totalPrice = number_format($detail['unit_price'] * $detail['quantity'], 2, thousands_separator:'');
            $discountAmount = 0.00;

            if (userCompany()->isDiscountBeforeVAT()) {
                $discount = ($detail['discount'] ?? 0.00) / 100;
                $discountAmount = number_format($totalPrice * $discount, 2, thousands_separator:'');
            }

            return $detail['total_price'] = number_format($totalPrice - $discountAmount, 2, thousands_separator:'');
        }
    }

    public static function getGrandTotalPrice($details)
    {
        return number_format(
            static::getSubTotalPrice($details) + (static::getVat($details)),
            2,
            thousands_separator:''
        );
    }

    public static function grandTotalPriceAfterDiscount($discount, $details)
    {
        $discount = ($discount ?? 0.00) / 100;
        $discountAmount = number_format(static::getGrandTotalPrice($details) * $discount, 2, thousands_separator:'');

        return number_format(
            static::getGrandTotalPrice($details) - $discountAmount,
            2,
            thousands_separator:''
        );
    }
}