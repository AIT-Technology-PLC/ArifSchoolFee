<?php

namespace App\Actions;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ApproveTransactionAction
{
    private const FAILED_MESSAGE = 'This transaction is already approved';

    private const SUCCESS_MESSAGE = 'You have approved this transaction successfully';

    public function execute($model, $notification, $permission)
    {
        if ($model->isApproved()) {
            return [false, static::FAILED_MESSAGE];
        }

        DB::transaction(function () use ($model, $notification, $permission) {
            $model->approve();

            Notification::send(
                notifiables($permission, $model->createdBy),
                new $notification($model)
            );
        });

        return [true, static::SUCCESS_MESSAGE];
    }
}
