<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class ProductPriceUpdated extends Notification
{
    use Queueable;

    private $price;

    public function __construct($price)
    {
        $this->price = $price;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        $message = Str::of($this->price->product->name)
            ->title()
            ->append(' cost changed and new price is created');

        return [
            'icon' => 'tags',
            'message' => $message,
            'endpoint' => '/products/' . $this->price->product->id . '/prices',
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        $message = Str::of($this->price->product->name)
            ->title()
            ->append(' cost changed and new price is created');

        return (new WebPushMessage)
            ->title('Cost changed and New Price Created')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body($message)
            ->badge(asset('pwa/pwa-512x512.png'))
            ->action('View', '/notifications/' . $notification->id)
            ->vibrate([500, 250, 500, 250]);
    }
}
