<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class AnnouncementApproved extends Notification
{
    use Queueable;

    public function __construct($announcement)
    {
        $this->announcement = $announcement;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'fas fa-rss',
            'message' => 'New announcement is posted on the board by ' . ucfirst($this->announcement->approvedBy->name),
            'endpoint' => '/announcements/' . $this->announcement->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Announcement Posted')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body('New announcement is posted on the board by ' . ucfirst($this->announcement->approvedBy->name))
            ->badge(asset('pwa/pwa-512x512.png'))
            ->action('View', '/notifications/' . $notification->id)
            ->vibrate([500, 250, 500, 250]);
    }
}
