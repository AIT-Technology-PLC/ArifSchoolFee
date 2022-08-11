<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class TransferApproved extends Notification
{
    use Queueable;

    public function __construct($transfer)
    {
        $this->transfer = $transfer;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'exchange-alt',
            'message' => 'Transfer has been approved by ' . ucfirst($this->transfer->approvedBy->name),
            'endpoint' => '/transfers/' . $this->transfer->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Transfer Approved')
            ->body('Transfer has been approved by ' . ucfirst($this->transfer->approvedBy->name))
            ->action('View', '/transfers/' . $this->transfer->id, 'exchange-alt')
            ->data(['id' => $notification->id]);
    }
}