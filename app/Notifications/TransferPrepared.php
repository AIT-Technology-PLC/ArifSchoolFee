<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TransferPrepared extends Notification
{
    use Queueable;

    public function __construct($transfer)
    {
        $this->transfer = $transfer;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'Approval request for Transfer prepared by ' . ucfirst($this->transfer->createdBy->name),
            'endpoint' => '/transfer/' . $this->transfer->id,
        ];
    }
}
