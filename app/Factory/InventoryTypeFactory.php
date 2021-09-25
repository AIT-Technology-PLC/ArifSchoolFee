<?php

namespace App\Factory;

use Illuminate\Support\Str;

class InventoryTypeFactory
{
    public static function make($productType)
    {
        $inventoryType = (string) Str::of($productType)
            ->prepend('App\\Services\\')
            ->append('Service')
            ->studly();

        return new $inventoryType();
    }
}
