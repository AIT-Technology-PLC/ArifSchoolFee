<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class SalePrepared extends Notification
{
    use Queueable;

    public function __construct($sale)
    {
        $this->sale = $sale;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'cash-register',
            'message' => 'Approval request for Invoice prepared by ' . ucfirst($this->sale->createdBy->name),
            'endpoint' => '/sales/' . $this->sale->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Invoice Prepared')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body('Approval request for Invoice prepared by ' . ucfirst($this->sale->createdBy->name))
            ->badge(asset('pwa/pwa-512x512.png'))
            ->action('View', '/notifications/' . $notification->id)
            ->vibrate([500, 250, 500, 250]);
    }
}
