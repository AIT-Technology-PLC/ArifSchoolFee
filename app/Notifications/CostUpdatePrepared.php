<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class CostUpdatePrepared extends Notification
{
    use Queueable;

    public function __construct($costUpdate)
    {
        $this->costUpdate = $costUpdate;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'tag',
            'message' => 'Approval request for Cost Update prepared by ' . ucfirst($this->costUpdate->createdBy->name),
            'endpoint' => '/cost-updates/' . $this->costUpdate->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Cost Update Prepared')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body('Approval request for Cost Update prepared by ' . ucfirst($this->costUpdate->createdBy->name))
            ->badge(asset('pwa/pwa-512x512.png'))
            ->action('View', '/notifications/' . $notification->id)
            ->vibrate([500, 250, 500, 250]);
    }
}
