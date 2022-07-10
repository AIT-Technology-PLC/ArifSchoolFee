<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReservationCancelled extends Notification
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
            'message' => 'Reservation has been cancelled by '.ucfirst($this->reservation->cancelledBy->name),
            'endpoint' => '/reservations/'.$this->reservation->id,
        ];
    }
}
