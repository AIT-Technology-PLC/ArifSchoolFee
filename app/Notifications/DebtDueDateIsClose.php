<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class DebtDueDateIsClose extends Notification
{
    use Queueable;

    private $totalDebts;

    public function __construct($totalDebts)
    {
        $this->totalDebts = $totalDebts;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        $message = Str::of($this->totalDebts)
            ->append(
                Str::plural(' debt', $this->totalDebts),
                ' will be due',
                ' after 7 days or less'
            );

        return [
            'icon' => 'fas fa-money-check-dollar',
            'message' => $message,
            'endpoint' => '/debts?type=due',
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        $message = Str::of($this->totalDebts)
            ->append(
                Str::plural(' debt', $this->totalDebts),
                ' will be due',
                ' after 7 days or less'
            );

        return (new WebPushMessage)
            ->title('Debt Due Date Close')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body($message)
            ->badge(asset('pwa/pwa-512x512.png'))
            ->action('View', '/notifications/' . $notification->id)
            ->vibrate([500, 250, 500, 250]);
    }
}
