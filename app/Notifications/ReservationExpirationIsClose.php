<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class ReservationExpirationIsClose extends Notification
{
    use Queueable;

    public function __construct($reservations)
    {
        $this->reservations = $reservations;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'archive',
            'message' => $this->reservations->count() . ' ' . Str::plural('reservation', $this->reservations->count()) . ' ' . ($this->reservations->count() == 1 ? 'has' : 'have') . ' 5 days or less remaining to be expired',
            'endpoint' => '/reservations',
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Reservation Expiration Close')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body($this->reservations->count() . ' ' . Str::plural('reservation', $this->reservations->count()) . ' ' . ($this->reservations->count() == 1 ? 'has' : 'have') . ' 5 days or less remaining to be expired')
            ->action('View', '/reservations/')
            ->vibrate([500, 250, 500, 250]);
    }
}