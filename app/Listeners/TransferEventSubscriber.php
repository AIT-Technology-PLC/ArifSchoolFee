<?php

namespace App\Listeners;

use App\Events\TransferApprovedEvent;
use App\Notifications\TransferApproved;
use Illuminate\Support\Facades\Notification;

class TransferEventSubscriber
{
    public function approved($event)
    {
        Notification::send(
            notifiables('Make Transfer', $event->transfer->createdBy),
            new TransferApproved($event->transfer)
        );
    }

    public function subscribe($events)
    {
        $events->listen(
            TransferApprovedEvent::class,
            [TransferEventSubscriber::class, 'approved']
        );
    }
}
