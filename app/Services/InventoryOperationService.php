<?php

namespace App\Services;

use App\Factory\InventoryTypeFactory;

class InventoryOperationService
{
    public static function add($details)
    {
        DB::transaction(function () use ($details) {
            foreach ($details as $detail) {
                $type = InventoryTypeFactory::make($detail);

                $type->add($detail);
            }
        });
    }
}
