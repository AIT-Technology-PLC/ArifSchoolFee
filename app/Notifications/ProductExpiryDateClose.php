<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class ProductExpiryDateClose extends Notification
{
    use Queueable;

    public function __construct($merchandiseBatches, $company)
    {
        $this->merchandiseBatches = $merchandiseBatches;

        $this->company = $company;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'fas fa-th',
            'message' => $this->merchandiseBatches->count() . ' ' . Str::plural('Product batch', $this->merchandiseBatches->count()) . ' ' . ($this->merchandiseBatches->count() == 1 ? 'has ' : 'have ') . $this->company->expiry_in_days . ' days or less remaining to be expired',
            'endpoint' => '/merchandise-batches',
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Product Batch Expiration Date Close')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body($this->merchandiseBatches->count() . ' ' . Str::plural('Product batch', $this->merchandiseBatches->count()) . ' ' . ($this->merchandiseBatches->count() == 1 ? 'has ' : 'have ') . $this->company->expiry_in_days . ' days or less remaining to be expired')
            ->badge(asset('pwa/pwa-512x512.png'))
            ->action('View', '/notifications/' . $notification->id)
            ->vibrate([500, 250, 500, 250]);
    }
}
