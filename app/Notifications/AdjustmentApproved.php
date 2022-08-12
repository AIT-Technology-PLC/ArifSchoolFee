<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class AdjustmentApproved extends Notification
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
            'message' => 'Inventory adjustment has been approved by ' . ucfirst($this->adjustment->approvedBy->name),
            'endpoint' => '/adjustments/' . $this->adjustment->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Inventory Adjustment Approved')
            ->body('Inventory adjustment has been approved by ' . ucfirst($this->adjustment->approvedBy->name))
            ->action('View', '/adjustments/' . $this->adjustment->id, 'eraser')
            ->data(['id' => $notification->id]);
    }
}