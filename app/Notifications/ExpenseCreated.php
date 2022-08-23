<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class ExpenseCreated extends Notification
{
    use Queueable;

    public function __construct($expense)
    {
        $this->expense = $expense;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => '',
            'message' => 'Expense has been created by ' . ucfirst($this->expense->createdBy->name),
            'endpoint' => '/expenses/' . $this->expense->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Expense Created')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body('Expense has been created by ' . ucfirst($this->expense->createdBy->name))
            ->badge(asset('pwa/pwa-512x512.png'))
            ->action('View', '/notifications/' . $notification->id)
            ->vibrate([500, 250, 500, 250]);
    }
}