<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class TransferRequested extends Notification
{
    use Queueable;

    private $transfer;

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
            'message' => 'New Transfer request is received from ' . ucfirst($this->transfer->transferredTo->name),
            'endpoint' => '/transfers/' . $this->transfer->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Transfer Requested')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body('New Transfer request is received from ' . ucfirst($this->transfer->transferredTo->name))
            ->action('View', '/transfers/' . $this->transfer->id)
            ->vibrate([500, 250, 500, 250]);
    }
}