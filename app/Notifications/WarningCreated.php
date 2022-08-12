<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class WarningCreated extends Notification
{
    use Queueable;

    public function __construct($warning)
    {
        $this->warning = $warning;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'fas fa-circle-exclamation',
            'message' => 'New warning has been created by ' . ucfirst($this->warning->createdBy->name),
            'endpoint' => '/warnings/' . $this->warning->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Warning Created')
            ->body('New warning has been created by ' . ucfirst($this->warning->createdBy->name))
            ->action('View', '/warnings/' . $this->warning->id, 'fas fa-circle-exclamation')
            ->data(['id' => $notification->id]);
    }
}