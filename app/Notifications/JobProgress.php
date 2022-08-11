<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class JobProgress extends Notification
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
            'message' => 'Job #' . $this->job->code . ' has new progress added by' . ucfirst($this->job->updatedBy->name),
            'endpoint' => '/jobs/' . $this->job->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Job Progress')
            ->body('Job #' . $this->job->code . ' has new progress added by' . ucfirst($this->job->updatedBy->name))
            ->action('View', '/jobs/' . $this->job->id, 'fas fa-cogs')
            ->data(['id' => $notification->id]);
    }
}