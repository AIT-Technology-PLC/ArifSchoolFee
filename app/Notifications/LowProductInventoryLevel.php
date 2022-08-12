<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class LowProductInventoryLevel extends Notification
{
    use Queueable;

    private $totalLimitedProducts;

    public function __construct($totalLimitedProducts)
    {
        $this->totalLimitedProducts = $totalLimitedProducts;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        $message = Str::of($this->totalLimitedProducts)
            ->append(
                ' ',
                Str::plural('product', $this->totalLimitedProducts),
                ' ',
                $this->totalLimitedProducts == 1 ? 'has' : 'have',
                ' reached low level'
            );

        return [
            'icon' => 'balance-scale',
            'message' => $message,
            'endpoint' => '/merchandises/available?level=limited',
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        $message = Str::of($this->totalLimitedProducts)
            ->append(
                ' ',
                Str::plural('product', $this->totalLimitedProducts),
                ' ',
                $this->totalLimitedProducts == 1 ? 'has' : 'have',
                ' reached low level'
            );

        return (new WebPushMessage)
            ->title('Low Product Inventory Level')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body($message)
            ->action('View', '/merchandises/available?level=limited')
            ->vibrate([500, 250, 500, 250]);
    }
}