<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class TransactionProductAdded extends Notification
{
    use Queueable;

    public function __construct($transactionDetail)
    {
        $this->transactionDetail = $transactionDetail;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => $this->transactionDetail['transaction']->pad->icon,
            'message' => $this->transactionDetail['product'] . ' in ' . str()->singular($this->transactionDetail['transaction']->pad->name) . ' #' . $this->transactionDetail['transaction']->code . ' is added to inventory by ' . authUser()->name,
            'endpoint' => '/transactions/' . $this->transactionDetail['transaction']->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Transaction Product Added')
            ->body($this->transactionDetail['product'] . ' in ' . str()->singular($this->transactionDetail['transaction']->pad->name) . ' #' . $this->transactionDetail['transaction']->code . ' is added to inventory by ' . authUser()->name)
            ->action('View', '/transactions/' . $this->transactionDetail['transaction']->id, $this->transaction->pad->icon)
            ->data(['id' => $notification->id]);
    }
}