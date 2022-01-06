<?php

namespace App\Listeners;

use App\Events\TransferApprovedEvent;
use App\Notifications\TransferApproved;
use App\Notifications\TransferRequested;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\Notification;

class TransferEventSubscriber
{
    public function approved($event)
    {
        Notification::send(
            Notifiables::byNextActionPermission('Make Transfer', $event->transfer->createdBy),
            new TransferApproved($event->transfer)
        );

        Notification::send(
            Notifiables::byPermissionAndWarehouse('Make Transfer', $event->transfer->transferred_from),
            new TransferRequested($event->transfer)
        );
    }

    public function subscribe($events)
    {
        return [
            TransferApprovedEvent::class => 'approved',
        ];
    }
}
