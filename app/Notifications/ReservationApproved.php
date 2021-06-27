<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReservationApproved extends Notification
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
            'message' => 'Reservation has been approved by ' . ucfirst($this->reservation->approvedBy->name),
            'endpoint' => '/reservations/' . $this->reservation->id,
        ];
    }
}
