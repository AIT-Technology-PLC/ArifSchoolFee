<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class AdjustmentMade extends Notification
{
    use Queueable;

    public function __construct($adjustment)
    {
        $this->adjustment = $adjustment;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'eraser',
            'message' => 'Inventory adjustment has been made successfully',
            'endpoint' => '/adjustments/' . $this->adjustment->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Inventory Adjustment Made')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body('Inventory adjustment has been made by ' . ucfirst($this->adjustment->createdBy->name))
            ->badge(asset('pwa/pwa-512x512.png'))
            ->action('View', '/notifications/' . $notification->id)
            ->vibrate([500, 250, 500, 250]);
    }
}
