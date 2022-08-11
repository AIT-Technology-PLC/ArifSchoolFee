<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class ReservationConverted extends Notification
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
            'message' => 'Approval request for delivery order issued from reservation by ' . ucfirst($this->reservation->convertedBy->name),
            'endpoint' => '/gdns/' . $this->reservation->reservable->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Delivery Order Approval Request')
            ->body('Approval request for delivery order issued from reservation by ' . ucfirst($this->reservation->convertedBy->name))
            ->action('View', '/reservations/' . $this->reservation->id, 'archive')
            ->data(['id' => $notification->id]);
    }
}