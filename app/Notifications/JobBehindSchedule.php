<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class JobBehindSchedule extends Notification
{
    use Queueable;

    public function __construct($jobs)
    {
        $this->jobs = $jobs;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'fas fa-cogs',
            'message' => $this->jobs->count() . ' ' . Str::plural('job', $this->jobs->count()) . ' ' . ($this->jobs->count() == 1 ? 'is' : 'are') . ' behind expected schedule date.',
            'endpoint' => '/jobs',
        ];
    }
}
