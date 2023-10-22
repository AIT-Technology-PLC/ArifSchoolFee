<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class SivSubtracted extends Notification
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
            'icon' => 'cash-register',
            'message' => 'Products in siv #' . $this->siv->code . ' are subtracted from inventory',
            'endpoint' => '/sivs/' . $this->siv->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Products in Siv Subtracted')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body('Products in siv #' . $this->siv->code . ' are subtracted from inventory')
            ->badge(asset('pwa/pwa-512x512.png'))
            ->action('View', '/notifications/' . $notification->id)
            ->vibrate([500, 250, 500, 250]);
    }
}
