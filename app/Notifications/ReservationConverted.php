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
            'message' => 'Reservation has been converted to Delivery Order by ' . ucfirst($this->reservation->convertedBy->name),
            'endpoint' => '/reservations/' . $this->reservation->id,
        ];
    }
}
