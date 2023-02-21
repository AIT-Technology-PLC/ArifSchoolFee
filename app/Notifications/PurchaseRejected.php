<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class PurchaseRejected extends Notification
{
    use Queueable;

    public function __construct($purchase)
    {
        $this->purchase = $purchase;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'fas fa-shopping-bag',
            'message' => 'Purchase has been rejected by ' . ucfirst($this->purchase->rejectedBy->name),
            'endpoint' => '/purchases/' . $this->purchase->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Purchase Rejected')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body('Purchase has been rejected by ' . ucfirst($this->purchase->rejectedBy->name))
            ->badge(asset('pwa/pwa-512x512.png'))
            ->action('View', '/notifications/' . $notification->id)
            ->vibrate([500, 250, 500, 250]);
    }
}