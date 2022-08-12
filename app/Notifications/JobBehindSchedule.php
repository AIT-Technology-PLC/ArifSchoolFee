<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class JobBehindSchedule extends Notification
{
    use Queueable;

    public function __construct($jobs)
    {
        $this->jobs = $jobs;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'fas fa-cogs',
            'message' => $this->jobs->count() . ' ' . Str::plural('job', $this->jobs->count()) . ' ' . ($this->jobs->count() == 1 ? 'is' : 'are') . ' behind schedule.',
            'endpoint' => '/jobs',
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Job Behind Schedule')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body($this->jobs->count() . ' ' . Str::plural('job', $this->jobs->count()) . ' ' . ($this->jobs->count() == 1 ? 'is' : 'are') . ' behind schedule.', )
            ->action('View', '/notifications/' . $notification->id)
            ->vibrate([500, 250, 500, 250]);
    }
}