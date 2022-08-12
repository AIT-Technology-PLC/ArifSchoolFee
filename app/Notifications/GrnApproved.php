<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class GrnApproved extends Notification
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
            'message' => 'GRN has been approved by ' . ucfirst($this->grn->approvedBy->name),
            'endpoint' => '/grns/' . $this->grn->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('GRN Approved')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body('GRN has been approved by ' . ucfirst($this->grn->approvedBy->name))
            ->action('View', '/grns/' . $this->grn->id)
            ->vibrate([500, 250, 500, 250]);
    }
}