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
        return [
            'message' => 'Transfer has been made successfully',
            'endpoint' => '/transfer/' . $this->transfer->id,
        ];
    }
}
