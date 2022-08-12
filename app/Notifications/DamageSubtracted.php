<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class DamageSubtracted extends Notification
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
            'message' => 'Damage claim products have been subtracted from inventory',
            'endpoint' => '/damages/' . $this->damage->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Damage Subtracted')
            ->body('Damage claim products have been subtracted from inventory')
            ->action('View', '/damages/' . $this->damage->id, 'bolt')
            ->data(['id' => $notification->id]);
    }
}