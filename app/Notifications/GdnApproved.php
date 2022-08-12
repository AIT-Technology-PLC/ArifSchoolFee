<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class GdnApproved extends Notification
{
    use Queueable;

    public function __construct($gdn)
    {
        $this->gdn = $gdn;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'file-invoice',
            'message' => 'Delivery Order has been approved by ' . ucfirst($this->gdn->approvedBy->name),
            'endpoint' => '/gdns/' . $this->gdn->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Delivery Order Approved')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body('Delivery Order has been approved by ' . ucfirst($this->gdn->approvedBy->name))
            ->action('View', '/gdns/' . $this->gdn->id)
            ->vibrate([500, 250, 500, 250]);
    }
}