<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class TransferMade extends Notification
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
        $message = 'Transfer has been added to ' . $this->transfer->transferredTo->name;

        if (!$this->transfer->isAdded()) {
            $message = 'Transfer has been subtracted from ' . $this->transfer->transferredFrom->name;
        }

        return [
            'icon' => 'exchange-alt',
            'message' => $message,
            'endpoint' => '/transfers/' . $this->transfer->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        $message = 'Transfer has been added to ' . $this->transfer->transferredTo->name;

        if (!$this->transfer->isAdded()) {
            $message = 'Transfer has been subtracted from ' . $this->transfer->transferredFrom->name;
        }

        return (new WebPushMessage)
            ->title('Transfer Made')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body($message)
            ->badge(asset('pwa/pwa-512x512.png'))
            ->action('View', '/notifications/' . $notification->id)
            ->vibrate([500, 250, 500, 250]);
    }
}
