<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class ExchangeApproved extends Notification
{
    use Queueable;

    public function __construct($exchange)
    {
        $this->exchange = $exchange;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'fa-solid fa-arrow-right-arrow-left',
            'message' => 'Exchange has been approved by ' . ucfirst($this->exchange->approvedBy->name),
            'endpoint' => '/exchanges/' . $this->exchange->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Exchange Approved')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body('Exchange has been approved by ' . ucfirst($this->exchange->approvedBy->name))
            ->badge(asset('pwa/pwa-512x512.png'))
            ->action('View', '/notifications/' . $notification->id)
            ->vibrate([500, 250, 500, 250]);
    }
}
