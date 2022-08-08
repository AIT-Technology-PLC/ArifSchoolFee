<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TransactionSubtracted extends Notification
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
            'message' => 'Products in ' . str()->singular($this->transaction->pad->name) . ' #' . $this->transaction->code . ' are subtracted from inventory by ' . $this->transaction->subtractedBy->name,
            'endpoint' => '/transactions/' . $this->transaction->id,
        ];
    }
}
