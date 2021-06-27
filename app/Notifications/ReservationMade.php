<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReservationMade extends Notification
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
            'message' => 'Reservation is made successfully and products have been reserved',
            'endpoint' => '/reservations/' . $this->reservation->id,
        ];
    }
}
