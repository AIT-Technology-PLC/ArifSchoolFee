<?php

namespace App\Actions;

use App\Utilities\Notifiables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class CancelTransactionAction
{
    private function sendNotification($model, $notification, $permission)
    {
        if (!is_null($notification) && !is_null($permission)) {
            Notification::send(
                Notifiables::byNextActionPermission($permission, $model->createdBy),
                new $notification($model)
            );
        }
    }

    public function execute($model, $notification = null, $permission = null)
    {
        if ($model->isCancelled()) {
            return [false, 'This transaction is already cancelled.'];
        }

        DB::transaction(function () use ($model, $notification, $permission) {
            $model->cancel();

            $this->sendNotification($model, $notification, $permission);
        });

        return [true, 'You have cancelled this transaction successfully.'];
    }
}