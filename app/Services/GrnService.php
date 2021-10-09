<?php

namespace App\Services;

use App\Services\InventoryOperationService;
use Illuminate\Support\Facades\DB;

class GrnService
{
    private const ADD_FAILED_MESSAGE = 'This transaction is already added to inventory.';

    private const APPROVE_FAILED_MESSAGE = 'This transaction is not approved yet.';

    public function add($grn)
    {
        if (!$grn->isApproved()) {
            return [false, static::APPROVE_FAILED_MESSAGE];
        }

        if ($grn->isAdded()) {
            return [false, static::ADD_FAILED_MESSAGE];
        }

        DB::transaction(function () use ($grn) {
            InventoryOperationService::add($grn->grnDetails);

            $grn->add();
        });

        return [true, ''];
    }
}
