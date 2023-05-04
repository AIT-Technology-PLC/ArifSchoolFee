<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class TransactionPrepared extends Notification
{
    use Queueable;

    private $message;

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
        if ($this->transaction->pad->isApprovable()) {
            $this->message = 'Approval request for ' . str()->singular($this->transaction->pad->name) . ' prepared by ' . $this->transaction->createdBy->name;
        }

        if ($this->transaction->pad->isApprovable()) {
            $this->message = 'New ' . str()->singular($this->transaction->pad->name) . ' has been prepared by ' . $this->transaction->createdBy->name;
        }
        return [
            'icon' => $this->transaction->pad->icon,
            'message' => $this->message,
            'endpoint' => '/transactions/' . $this->transaction->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Transaction Prepared')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body($this->message)
            ->badge(asset('pwa/pwa-512x512.png'))
            ->action('View', '/notifications/' . $notification->id)
            ->vibrate([500, 250, 500, 250]);
    }
}
