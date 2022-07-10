<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class ReservationExpirationIsClose extends Notification
{
    use Queueable;

    public function __construct($reservations)
    {
        $this->reservations = $reservations;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'archive',
            'message' => $this->reservations->count().' '.Str::plural('reservation', $this->reservations->count()).' '.($this->reservations->count() == 1 ? 'has' : 'have').' 5 days or less remaining to be expired',
            'endpoint' => '/reservations',
        ];
    }
}
