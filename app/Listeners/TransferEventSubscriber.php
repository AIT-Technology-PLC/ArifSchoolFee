<?php

namespace App\Listeners;

use App\Events\TransferApprovedEvent;
use App\Notifications\TransferApproved;
use App\Notifications\TransferRequested;
use Illuminate\Support\Facades\Notification;

class TransferEventSubscriber
{
    public function approved($event)
    {
        Notification::send(
            notifiables('Make Transfer', $event->transfer->createdBy),
            new TransferApproved($event->transfer)
        );

        Notification::send(
            notifiablesByBranch('Make Transfer', 'subtract', $event->transfer->transferred_from),
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
