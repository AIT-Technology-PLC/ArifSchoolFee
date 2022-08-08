<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TransactionProductAdded extends Notification
{
    use Queueable;

    public function __construct($transactionDetail)
    {
        $this->transactionDetail = $transactionDetail;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => $this->transactionDetail['transaction']->pad->icon,
            'message' => $this->transactionDetail['product'] . ' in ' . str()->singular($this->transactionDetail['transaction']->pad->name) . ' #' . $this->transactionDetail['transaction']->code . ' is added to inventory by ' . authUser()->name,
            'endpoint' => '/transactions/' . $this->transactionDetail['transaction']->id,
        ];
    }
}
