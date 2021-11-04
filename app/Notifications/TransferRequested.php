<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TransferRequested extends Notification
{
    use Queueable;

    private $transfer;

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
            'message' => 'New Transfer request is received from ' . ucfirst($this->transfer->transferredTo->name),
            'endpoint' => '/transfers/' . $this->transfer->id,
        ];
    }
}
