<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class AnnouncementCreated extends Notification
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
            'message' => 'New announcement has been created by ' . ucfirst($this->announcement->createdBy->name),
            'endpoint' => '/announcements/' . $this->announcement->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Announcement Created')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body('New announcement has been created by ' . ucfirst($this->announcement->createdBy->name))
            ->action('View', '/announcements/' . $this->announcement->id)
            ->vibrate([500, 250, 500, 250]);
    }
}