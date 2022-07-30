<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AnnouncementCreated extends Notification
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
            'message' => 'New announcement has been created by ' . ucfirst($this->announcement->createdBy->name),
            'endpoint' => '/announcements/' . $this->announcement->id,
        ];
    }
}
