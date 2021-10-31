<?php

namespace App\Services;

use App\Services\InventoryOperationService;
use Illuminate\Support\Facades\DB;

class GrnService
{
    public function add($grn)
    {
        if (!$grn->isApproved()) {
            return [false, 'This Goods Received Note is not approved yet.'];
        }

        if ($grn->isAdded()) {
            return [false, 'This Goods Received Note is already added to inventory.'];
        }

        DB::transaction(function () use ($grn) {
            InventoryOperationService::add($grn->grnDetails);

            $grn->add();
        });

        return [true, ''];
    }
}
