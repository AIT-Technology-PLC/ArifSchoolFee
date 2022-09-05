<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class LeaveApproved extends Notification
{
    use Queueable;

    public function __construct($leaf)
    {
        $this->leaf = $leaf;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'umbrella-beach',
            'message' => 'Leave has been approved by ' . ucfirst($this->leaf->approvedBy->name),
            'endpoint' => '/leaves/' . $this->leaf->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Leave Approved')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body('Leave has been approved by ' . ucfirst($this->leaf->approvedBy->name))
            ->badge(asset('pwa/pwa-512x512.png'))
            ->action('View', '/notifications/' . $notification->id)
            ->vibrate([500, 250, 500, 250]);
    }
}
