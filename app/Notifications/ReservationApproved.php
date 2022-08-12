<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class ReservationApproved extends Notification
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
            'message' => 'Reservation has been approved by ' . ucfirst($this->reservation->approvedBy->name),
            'endpoint' => '/reservations/' . $this->reservation->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Reservation Approved')
            ->body('Reservation has been approved by ' . ucfirst($this->reservation->approvedBy->name))
            ->action('View', '/reservations/' . $this->reservation->id, 'archive')
            ->data(['id' => $notification->id]);
    }
}