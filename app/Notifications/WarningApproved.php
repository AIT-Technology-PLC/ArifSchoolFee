<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class WarningApproved extends Notification
{
    use Queueable;

    public function __construct($warning)
    {
        $this->warning = $warning;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'fas fa-circle-exclamation',
            'message' => 'Warning has been approved by ' . ucfirst($this->warning->approvedBy->name),
            'endpoint' => '/warnings/' . $this->warning->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Warning Approved')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body('Warning has been approved by ' . ucfirst($this->warning->approvedBy->name))
            ->action('View', '/notifications/' . $notification->id)
            ->vibrate([500, 250, 500, 250]);
    }
}
