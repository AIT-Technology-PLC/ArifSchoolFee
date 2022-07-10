<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PurchasePrepared extends Notification
{
    use Queueable;

    public function __construct(private $purchase)
    {
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'shopping-bag',
            'message' => 'Approval request for purchase request prepared by '.ucfirst($this->purchase->createdBy->name),
            'endpoint' => '/purchases/'.$this->purchase->id,
        ];
    }
}
