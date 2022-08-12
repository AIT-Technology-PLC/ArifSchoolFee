<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class EarningCreated extends Notification
{
    use Queueable;

    public function __construct($earning)
    {
        $this->earning = $earning;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'fas fa-hand-holding-dollar',
            'message' => 'New earning has been created by ' . ucfirst($this->earning->createdBy->name),
            'endpoint' => '/earnings/' . $this->earning->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Earning Created')
            ->body('New earning has been created by ' . ucfirst($this->earning->createdBy->name))
            ->action('View', '/earnings/' . $this->earning->id, 'fas fa-hand-holding-dollar')
            ->data(['id' => $notification->id]);
    }
}