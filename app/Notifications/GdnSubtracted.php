<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class GdnSubtracted extends Notification
{
    use Queueable;

    public function __construct($gdn)
    {
        $this->gdn = $gdn;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'file-invoice',
            'message' => 'Products in delivery order #' . $this->gdn->code . ' are subtracted from inventory',
            'endpoint' => '/gdns/' . $this->gdn->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Products in Delivery Order Subtracted')
            ->body('Products in delivery order #' . $this->gdn->code . ' are subtracted from inventory')
            ->action('View', '/gdns/' . $this->gdn->id, 'file-invoice')
            ->data(['id' => $notification->id]);
    }
}