<?php

namespace App\Factory;

use Illuminate\Support\Str;

class InventoryTypeFactory
{
    public static function make($detail)
    {
        $inventoryType = Str::of($detail->product->type)
            ->append('Service')
            ->studly();

        return new $inventoryType();
    }
}
