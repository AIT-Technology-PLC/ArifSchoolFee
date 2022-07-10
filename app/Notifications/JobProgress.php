<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class JobProgress extends Notification
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
            'message' => 'Job #'.$this->job->code.' has new progress added by'.ucfirst($this->job->updatedBy->name),
            'endpoint' => '/jobs/'.$this->job->id,
        ];
    }
}
