<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TransferMade extends Notification
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
        $message = 'Transfer has been added to '.$this->transfer->transferredTo->name;

        if (! $this->transfer->isAdded()) {
            $message = 'Transfer has been subtracted from '.$this->transfer->transferredFrom->name;
        }

        return [
            'icon' => 'exchange-alt',
            'message' => $message,
            'endpoint' => '/transfers/'.$this->transfer->id,
        ];
    }
}
