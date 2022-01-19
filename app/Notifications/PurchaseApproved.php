<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PurchaseApproved extends Notification
{
    use Queueable;

    public function __construct(private $purchase)
    {}

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'shopping-bag',
            'message' => 'Purchase #' . $this->purchase->code . 'is approved by' . ucfirst($this->purchase->approvedBy->name),
            'endpoint' => '/purchases/' . $this->purchase->id,
        ];
    }
}
