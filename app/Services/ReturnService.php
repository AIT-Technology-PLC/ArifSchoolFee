<?php

namespace App\Services;

use App\Services\InventoryOperationService;
use Illuminate\Support\Facades\DB;

class ReturnService
{
    public function add($return)
    {
        if (!$return->isApproved()) {
            return [false, 'This transaction is not approved yet.'];
        }

        if ($return->isAdded()) {
            return [false, 'This transaction is already added to inventory.'];
        }

        DB::transaction(function () use ($return) {
            InventoryOperationService::add($return->returnDetails);

            $return->add();
        });

        return [true, ''];
    }
}
