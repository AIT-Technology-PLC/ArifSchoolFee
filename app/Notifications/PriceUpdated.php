<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class PriceUpdated extends Notification
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
            ->append(' price is updated.');

        return [
            'icon' => 'tags',
            'message' => $message,
            'endpoint' => '/prices',
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        $message = Str::of($this->price->product->name)
            ->title()
            ->append(' price is updated.');

        return (new WebPushMessage)
            ->title('Price Updated')
            ->body($message)
            ->action('View', '/prices', 'tags')
            ->data(['id' => $notification->id]);
    }
}