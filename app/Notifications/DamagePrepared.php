<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class DamagePrepared extends Notification
{
    use Queueable;

    public function __construct($damage)
    {
        $this->damage = $damage;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'bolt',
            'message' => 'Approval request for Damage claim prepared by ' . ucfirst($this->damage->createdBy->name),
            'endpoint' => '/damages/' . $this->damage->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Damage Prepared')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body('Approval request for Damage claim prepared by ' . ucfirst($this->damage->createdBy->name))
            ->action('View', '/damages/' . $this->damage->id)
            ->vibrate([500, 250, 500, 250]);
    }
}