<?php

namespace App\Actions;

use App\Services\InventoryOperationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class AddToInventoryAction
{
    private const ADD_FAILED_MESSAGE = 'This transaction is already added to inventory';

    private const APPROVE_FAILED_MESSAGE = 'This transaction is not approved yet.';

    private const SUCCESS_MESSAGE = 'Products have been added to inventory successfully';

    public function execute($model, $notification, $permission)
    {
        if (!$model->isApproved()) {
            return [false, static::APPROVE_FAILED_MESSAGE];
        }

        if ($model->isAdded()) {
            return [false, static::ADD_FAILED_MESSAGE];
        }

        DB::transaction(function () use ($model, $notification, $permission) {
            InventoryOperationService::add($model->details());

            $model->add();

            Notification::send(
                notifiables($permission, $model->createdBy),
                new $notification($model)
            );
        });

        return [true, static::SUCCESS_MESSAGE];
    }
}
