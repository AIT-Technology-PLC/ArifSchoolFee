<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TransferApproved extends Notification
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
            'icon' => 'exchange-alt',
            'message' => 'Transfer has been approved by '.ucfirst($this->transfer->approvedBy->name),
            'endpoint' => '/transfers/'.$this->transfer->id,
        ];
    }
}
