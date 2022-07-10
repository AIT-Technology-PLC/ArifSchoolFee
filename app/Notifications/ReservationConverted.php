<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReservationConverted extends Notification
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
            'message' => 'Approval request for delivery order issued from reservation by '.ucfirst($this->reservation->convertedBy->name),
            'endpoint' => '/gdns/'.$this->reservation->reservable->id,
        ];
    }
}
