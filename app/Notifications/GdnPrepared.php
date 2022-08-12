<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class GdnPrepared extends Notification
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
            'message' => 'Approval request for Delivery Order prepared by ' . ucfirst($this->gdn->createdBy->name),
            'endpoint' => '/gdns/' . $this->gdn->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Delivery Order Prepared')
            ->body('Approval request for Delivery Order prepared by ' . ucfirst($this->gdn->createdBy->name))
            ->action('View', '/gdns/' . $this->gdn->id, 'file-invoice')
            ->data(['id' => $notification->id]);
    }
}