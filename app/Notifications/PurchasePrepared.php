<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class PurchasePrepared extends Notification
{
    use Queueable;

    public function __construct(private $purchase)
    {
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'shopping-bag',
            'message' => 'Approval request for purchase request prepared by ' . ucfirst($this->purchase->createdBy->name),
            'endpoint' => '/purchases/' . $this->purchase->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Purchase Prepared')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body('Approval request for purchase request prepared by ' . ucfirst($this->purchase->createdBy->name))
            ->badge(asset('pwa/pwa-512x512.png'))
            ->action('View', '/notifications/' . $notification->id)
            ->vibrate([500, 250, 500, 250]);
    }
}
