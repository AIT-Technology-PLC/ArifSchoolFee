<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class DamageApproved extends Notification
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
            'message' => 'Damage claim has been approved by ' . ucfirst($this->damage->approvedBy->name),
            'endpoint' => '/damages/' . $this->damage->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Damage Approved')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body('Damage claim has been approved by ' . ucfirst($this->damage->approvedBy->name))
            ->action('View', '/damages/' . $this->damage->id)
            ->vibrate([500, 250, 500, 250]);
    }
}