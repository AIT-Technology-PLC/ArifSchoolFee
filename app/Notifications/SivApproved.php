<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class SivApproved extends Notification
{
    use Queueable;

    public function __construct($siv)
    {
        $this->siv = $siv;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'file-export',
            'message' => 'SIV has been approved by ' . ucfirst($this->siv->approvedBy->name),
            'endpoint' => '/sivs/' . $this->siv->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('SIV Approved')
            ->body('SIV has been approved by ' . ucfirst($this->siv->approvedBy->name))
            ->action('View', '/sivs/' . $this->siv->id, 'file-export')
            ->data(['id' => $notification->id]);
    }
}