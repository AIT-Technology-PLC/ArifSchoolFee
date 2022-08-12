<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class CompensationAdjustmentCancelled extends Notification
{
    use Queueable;

    public function __construct($compensationAdjustment)
    {
        $this->compensationAdjustment = $compensationAdjustment;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'fa-solid fa-circle-dollar-to-slot',
            'message' => 'Compensation Adjustment has been cancelled by ' . ucfirst($this->compensationAdjustment->cancelledBy->name),
            'endpoint' => '/compensation-adjustments/' . $this->compensationAdjustment->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Compensation Adjustment Cancelled')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body('Compensation Adjustment has been cancelled by ' . ucfirst($this->compensationAdjustment->cancelledBy->name))
            ->badge(asset('pwa/pwa-512x512.png'))
            ->action('View', '/notifications/' . $notification->id)
            ->vibrate([500, 250, 500, 250]);
    }
}
