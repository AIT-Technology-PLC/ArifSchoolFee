<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class JobCreated extends Notification
{
    use Queueable;

    public function __construct($job)
    {
        $this->job = $job;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'fas fa-cogs',
            'message' => 'New job has been created by ' . ucfirst($this->job->createdBy->name),
            'endpoint' => '/jobs/' . $this->job->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Job Created')
            ->body('New job has been created by ' . ucfirst($this->job->createdBy->name))
            ->action('View', '/jobs/' . $this->job->id, 'fas fa-cogs')
            ->data(['id' => $notification->id]);
    }
}