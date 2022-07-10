<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReservationPrepared extends Notification
{
    use Queueable;

    public function __construct($reservation)
    {
        $this->reservation = $reservation;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'archive',
            'message' => 'Approval request for reservation prepared by '.ucfirst($this->reservation->createdBy->name),
            'endpoint' => '/reservations/'.$this->reservation->id,
        ];
    }
}
