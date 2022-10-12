<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class PriceIncrementCreated extends Notification
{
    use Queueable;

    public function __construct($priceIncrement)
    {
        $this->priceIncrement = $priceIncrement;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'tags',
            'message' => 'New Price Increment has been created by ' . ucfirst($this->priceIncrement->createdBy->name),
            'endpoint' => '/price-increments/' . $this->priceIncrement->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Price Increment Created')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body('New Price Increment has been created by ' . ucfirst($this->priceIncrement->createdBy->name))
            ->badge(asset('pwa/pwa-512x512.png'))
            ->action('View', '/notifications/' . $notification->id)
            ->vibrate([500, 250, 500, 250]);
    }
}