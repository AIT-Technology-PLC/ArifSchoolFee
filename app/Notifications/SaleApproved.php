<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class SaleApproved extends Notification
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
            'message' => 'Invoice #' . $this->sale->code . ' has been approved by ' . ucfirst($this->sale->approvedBy->name),
            'endpoint' => '/sales/' . $this->sale->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Invoice Approved')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body('Invoice has been approved by ' . ucfirst($this->sale->approvedBy->name))
            ->badge(asset('pwa/pwa-512x512.png'))
            ->action('View', '/notifications/' . $notification->id)
            ->vibrate([500, 250, 500, 250]);
    }
}
