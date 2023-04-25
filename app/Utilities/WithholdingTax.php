<?php

namespace App\Utilities;

class WithholdingTax
{
    public static function ethiopia()
    {
        return [
            'rules' => [
                'Services' => 3000,
                'Finished Goods' => 10000,
                'Raw Material' => 10000,
            ],

            'tax_rate' => 0.02,
        ];
    }
}
