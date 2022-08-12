<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class CompensationAdjustmentCreated extends Notification
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
            'message' => 'Compensation Adjustment has been created by ' . ucfirst($this->compensationAdjustment->createdBy->name),
            'endpoint' => '/compensation-adjustments/' . $this->compensationAdjustment->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Compensation Adjustment Created')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body('Compensation Adjustment has been created by ' . ucfirst($this->compensationAdjustment->createdBy->name))
            ->action('View', '/compensation-adjustments/' . $this->compensationAdjustment->id)
            ->vibrate([500, 250, 500, 250]);
    }
}