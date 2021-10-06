<?php

namespace App\Actions;

use App\Services\InventoryOperationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class SubtractFromInventoryAction
{
    private const SUBTRACT_FAILED_MESSAGE = 'This transaction is already subtracted from inventory';

    private const APPROVE_FAILED_MESSAGE = 'This transaction is not approved yet.';

    private const SUCCESS_MESSAGE = 'Products have been subtracted from inventory successfully';

    public function execute($model, $notification, $permission, $from = 'available')
    {
        if (!$model->isApproved()) {
            return [false, static::APPROVE_FAILED_MESSAGE];
        }

        if ($model->isSubtracted()) {
            return [false, static::SUBTRACT_FAILED_MESSAGE];
        }

        DB::transaction(function () use ($model, $notification, $permission, $from) {
            $result = InventoryOperationService::subtract($model->details(), $from);

            if (!$result['isSubtracted']) {
                return [false, $result['unavailableProducts']];
            }

            $model->subtract();

            Notification::send(
                notifiables($permission, $model->createdBy),
                new $notification($model)
            );
        });

        return [true, static::SUCCESS_MESSAGE];
    }
}
