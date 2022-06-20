<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class JobApproved extends Notification
{
    use Queueable;

    public function __construct($job)
    {
        $this->job = $job;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'fas fa-cogs',
            'message' => 'Job has been approved by ' . ucfirst($this->job->approvedBy->name),
            'endpoint' => '/jobs/' . $this->job->id,
        ];
    }
}
