<?php

namespace App\Services;

use App\Factory\InventoryTypeFactory;

class InventoryOperationService
{
    public static function add($details)
    {
        return DB::transaction(function () use ($details) {
            foreach ($details as $detail) {
                $type = InventoryTypeFactory::make($detail);

                $isAdded = $type->add($detail);

                if (!$isAdded) {
                    return false;
                }
            }
        });
    }
}
