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
            ->body('New announcement is posted on the board by ' . ucfirst($this->announcement->approvedBy->name))
            ->action('View', '/announcements/' . $this->announcement->id, 'fas fa-rss')
            ->data(['id' => $notification->id]);
    }
}