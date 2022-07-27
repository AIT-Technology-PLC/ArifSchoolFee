<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ExpenseClaimRejected extends Notification
{
    use Queueable;

    public function __construct($expenseClaim)
    {
        $this->expenseClaim = $expenseClaim;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'fa-solid fa-file-invoice-dollar',
            'message' => 'Expense Claim has been rejected by ' . ucfirst($this->expenseClaim->rejectedBy->name),
            'endpoint' => '/expense-claims/' . $this->expenseClaim->id,
        ];
    }
}