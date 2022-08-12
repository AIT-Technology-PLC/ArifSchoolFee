<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class TransactionAdded extends Notification
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
            'message' => 'Products in ' . str()->singular($this->transaction->pad->name) . ' #' . $this->transaction->code . ' are added to inventory by ' . $this->transaction->addedBy->name,
            'endpoint' => '/transactions/' . $this->transaction->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Transaction Added')
            ->body('Products in ' . str()->singular($this->transaction->pad->name) . ' #' . $this->transaction->code . ' are added to inventory by ' . $this->transaction->addedBy->name)
            ->action('View', '/transactions/' . $this->transaction->id, $this->transaction->pad->icon)
            ->data(['id' => $notification->id]);
    }
}