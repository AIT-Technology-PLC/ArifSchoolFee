<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AnnouncementApproved extends Notification
{
    use Queueable;

    public function __construct($announcement)
    {
        $this->announcement = $announcement;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'fas fa-rss',
            'message' => 'New announcement is posted on the board by ' . ucfirst($this->announcement->approvedBy->name),
            'endpoint' => '/announcements/' . $this->announcement->id,
        ];
    }
}
