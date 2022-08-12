<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class ExpenseClaimCreated extends Notification
{
    use Queueable;

    public function __construct($expenseClaim)
    {
        $this->expenseClaim = $expenseClaim;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'fa-solid fa-file-invoice-dollar',
            'message' => 'Expense Claim has been created by ' . ucfirst($this->expenseClaim->createdBy->name),
            'endpoint' => '/expense-claims/' . $this->expenseClaim->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Expense Claim Created')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body('Expense Claim has been created by ' . ucfirst($this->expenseClaim->createdBy->name))
            ->action('View', '/expense-claims/' . $this->expenseClaim->id)
            ->vibrate([500, 250, 500, 250]);
    }
}