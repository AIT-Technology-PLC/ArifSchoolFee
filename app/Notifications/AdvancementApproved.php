<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class AdvancementApproved extends Notification
{
    use Queueable;

    public function __construct($advancement)
    {
        $this->advancement = $advancement;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'fas fa-arrows-up-down',
            'message' => 'Advancement has been approved by ' . ucfirst($this->advancement->approvedBy->name),
            'endpoint' => '/advancements/' . $this->advancement->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Advancement Approved')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body('Advancement has been approved by ' . ucfirst($this->advancement->approvedBy->name))
            ->action('View', '/notifications/' . $notification->id)
            ->vibrate([500, 250, 500, 250]);
    }
}
