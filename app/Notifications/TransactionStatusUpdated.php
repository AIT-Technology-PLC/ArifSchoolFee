<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class TransactionStatusUpdated extends Notification
{
    use Queueable;

    public function __construct($transaction)
    {
        $this->transaction = $transaction;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'info',
            'message' => str()->singular($this->transaction->pad->name) . ' Status updated to ' . $this->transaction->status . ' by ' . ucfirst($this->transaction->updatedBy->name),
            'endpoint' => '/transactions/' . $this->transaction->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Transaction Status Updated')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body(str()->singular($this->transaction->pad->name) . ' Status updated to ' . $this->transaction->status . ' by ' . ucfirst($this->transaction->updatedBy->name))
            ->action('View', '/transactions/' . $this->transaction->id)
            ->vibrate([500, 250, 500, 250]);
    }
}