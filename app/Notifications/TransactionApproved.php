<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class TransactionApproved extends Notification
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
            'icon' => $this->transaction->pad->icon,
            'message' => str()->singular($this->transaction->pad->name) . ' #' . $this->transaction->code . ' is approved by ' . $this->transaction->approvedBy->name,
            'endpoint' => '/transactions/' . $this->transaction->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Transaction Approved')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body(str()->singular($this->transaction->pad->name) . ' #' . $this->transaction->code . ' is approved by ' . $this->transaction->approvedBy->name)
            ->action('View', '/notifications/' . $notification->id)
            ->vibrate([500, 250, 500, 250]);
    }
}
