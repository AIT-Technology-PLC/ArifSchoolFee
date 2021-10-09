<?php

namespace App\Services;

use App\Services\InventoryOperationService;
use Illuminate\Support\Facades\DB;

class ReturnService
{
    private const ADD_FAILED_MESSAGE = 'This transaction is already added to inventory.';

    private const APPROVE_FAILED_MESSAGE = 'This transaction is not approved yet.';

    public function add($return)
    {
        if (!$return->isApproved()) {
            return [false, static::APPROVE_FAILED_MESSAGE];
        }

        if ($return->isAdded()) {
            return [false, static::ADD_FAILED_MESSAGE];
        }

        DB::transaction(function () use ($return) {
            InventoryOperationService::add($return->returnDetails);

            $return->add();
        });

        return [true, ''];
    }
}
