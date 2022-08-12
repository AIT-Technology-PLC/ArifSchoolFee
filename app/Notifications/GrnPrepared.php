<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class GrnPrepared extends Notification
{
    use Queueable;

    public function __construct($grn)
    {
        $this->grn = $grn;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'file-import',
            'message' => 'Approval request for GRN prepared by ' . ucfirst($this->grn->createdBy->name),
            'endpoint' => '/grns/' . $this->grn->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('GRN Prepared')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body('Approval request for GRN prepared by ' . ucfirst($this->grn->createdBy->name))
            ->action('View', '/notifications/' . $notification->id)
            ->vibrate([500, 250, 500, 250]);
    }
}