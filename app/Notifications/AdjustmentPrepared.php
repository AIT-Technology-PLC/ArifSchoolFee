<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class AdjustmentPrepared extends Notification
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
            'message' => 'Approval request for inventory adjustment prepared by ' . ucfirst($this->adjustment->createdBy->name),
            'endpoint' => '/adjustments/' . $this->adjustment->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Inventory Adjustment Prepared')
            ->body('Approval request for inventory adjustment prepared by ' . ucfirst($this->adjustment->createdBy->name))
            ->action('View', '/adjustments/' . $this->adjustment->id, 'eraser')
            ->data(['id' => $notification->id]);
    }
}