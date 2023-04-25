<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class SupplierBusinessLicenceExpiryDateClose extends Notification
{
    use Queueable;

    private $totalBusinessLicence;

    public function __construct($totalBusinessLicence)
    {
        $this->totalBusinessLicence = $totalBusinessLicence;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'fas fa-user',
            'message' => $this->totalBusinessLicence->count() . ' ' . Str::plural('Supplier Business Licence', $this->totalBusinessLicence->count()) . ' ' . ($this->totalBusinessLicence->count() == 1 ? 'has ' : 'have ') . 30 . ' days or less remaining to be expire',
            'endpoint' => '/suppliers?type=business_license_expiry_due',
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Supplier Business Licence Expiration Date Close')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body($this->totalBusinessLicence->count() . ' ' . Str::plural('Supplier Business Licence', $this->totalBusinessLicence->count()) . ' ' . ($this->totalBusinessLicence->count() == 1 ? 'has ' : 'have ') . 30 . ' days or less remaining to be expire')
            ->badge(asset('pwa/pwa-512x512.png'))
            ->action('View', '/notifications/' . $notification->id)
            ->vibrate([500, 250, 500, 250]);
    }
}
