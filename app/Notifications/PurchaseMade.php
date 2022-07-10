<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PurchaseMade extends Notification
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
            'message' => 'Purchase #'.$this->purchase->code.'is assigned as purchased by'.ucfirst($this->purchase->purchasedBy->name),
            'endpoint' => '/purchases/'.$this->purchase->id,
        ];
    }
}
