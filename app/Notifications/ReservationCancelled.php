<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class ReservationCancelled extends Notification
{
    use Queueable;

    public function __construct($reservation)
    {
        $this->reservation = $reservation;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'archive',
            'message' => 'Reservation has been cancelled by ' . ucfirst($this->reservation->cancelledBy->name),
            'endpoint' => '/reservations/' . $this->reservation->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Reservation Cancelled')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body('Reservation has been cancelled by ' . ucfirst($this->reservation->cancelledBy->name))
            ->action('View', '/reservations/' . $this->reservation->id)
            ->vibrate([500, 250, 500, 250]);
    }
}