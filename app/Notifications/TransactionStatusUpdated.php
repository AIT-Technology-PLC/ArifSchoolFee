<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TransactionStatusUpdated extends Notification
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
            'icon' => 'info',
            'message' => str()->singular($this->transaction->pad->name) . ' Status updated to ' . $this->transaction->status . ' by ' . ucfirst($this->transaction->updatedBy->name),
            'endpoint' => '/transactions/' . $this->transaction->id,
        ];
    }
}
