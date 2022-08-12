<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class ReturnApproved extends Notification
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
            'message' => 'Return voucher has been approved by ' . ucfirst($this->return->approvedBy->name),
            'endpoint' => '/returns/' . $this->return->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Return Approved')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body('Return voucher has been approved by ' . ucfirst($this->return->approvedBy->name))
            ->badge(asset('pwa/pwa-512x512.png'))
            ->action('View', '/notifications/' . $notification->id)
            ->vibrate([500, 250, 500, 250]);
    }
}
