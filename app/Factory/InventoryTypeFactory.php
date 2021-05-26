<?php

namespace App\Factory;

use Illuminate\Support\Str;

class InventoryTypeFactory
{
    public static function make($detail)
    {
        $inventoryType = (string) Str::of($detail->product->type)
            ->prepend('App\\Services\\')
            ->append('Service')
            ->studly();

        return new $inventoryType();
    }
}
