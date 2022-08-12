<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class ReturnAdded extends Notification
{
    use Queueable;

    public function __construct($return)
    {
        $this->return = $return;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'arrow-alt-circle-left',
            'message' => 'Returned products have been added to inventory',
            'endpoint' => '/returns/' . $this->return->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Return Added')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body('Returned products have been added to inventory')
            ->action('View', '/returns/' . $this->return->id)
            ->vibrate([500, 250, 500, 250]);
    }
}