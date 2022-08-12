<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class SivPrepared extends Notification
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
            'message' => 'Approval request for SIV prepared by ' . ucfirst($this->siv->createdBy->name),
            'endpoint' => '/sivs/' . $this->siv->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('SIV Prepared')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body('Approval request for SIV prepared by ' . ucfirst($this->siv->createdBy->name))
            ->action('View', '/sivs/' . $this->siv->id)
            ->vibrate([500, 250, 500, 250]);
    }
}