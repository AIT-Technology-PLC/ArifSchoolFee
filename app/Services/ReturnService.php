<?php

namespace App\Services;

use App\Notifications\ReturnAdded;
use App\Services\InventoryOperationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

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
            InventoryOperationService::add($return->details());

            $return->add();

            Notification::send(notifiables('Approve Return', $return->createdBy), new ReturnAdded($return));
        });

        return [true, ''];
    }
}
