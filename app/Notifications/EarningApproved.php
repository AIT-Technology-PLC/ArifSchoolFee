<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class EarningApproved extends Notification
{
    use Queueable;

    public function __construct($earning)
    {
        $this->earning = $earning;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'fas fa-hand-holding-dollar',
            'message' => 'Earning has been approved by ' . ucfirst($this->earning->approvedBy->name),
            'endpoint' => '/earnings/' . $this->earning->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Earning Approved')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body('Earning has been approved by ' . ucfirst($this->earning->approvedBy->name))
            ->badge(asset('pwa/pwa-512x512.png'))
            ->action('View', '/notifications/' . $notification->id)
            ->vibrate([500, 250, 500, 250]);
    }
}
