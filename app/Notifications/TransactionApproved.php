<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TransactionApproved extends Notification
{
    use Queueable;

    public function __construct($transaction)
    {
        $this->transaction = $transaction;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => $this->transaction->pad->icon,
            'message' => str()->singular($this->transaction->pad->name) . ' #' . $this->transaction->code . ' is approved by ' . $this->transaction->approvedBy->name,
            'endpoint' => '/transactions/' . $this->transaction->id,
        ];
    }
}
