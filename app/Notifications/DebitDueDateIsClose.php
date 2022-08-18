<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class DebitDueDateIsClose extends Notification
{
    use Queueable;

    private $totalDebits;

    public function __construct($totalDebits)
    {
        $this->totalDebits = $totalDebits;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        $message = Str::of($this->totalDebits)
            ->append(
                Str::plural(' debit', $this->totalDebits),
                ' will be due',
                ' after 7 days or less'
            );

        return [
            'icon' => 'fas fa-money-check',
            'message' => $message,
            'endpoint' => '/debits?type=due',
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        $message = Str::of($this->totalDebits)
            ->append(
                Str::plural(' debit', $this->totalDebits),
                ' will be due',
                ' after 7 days or less'
            );

        return (new WebPushMessage)
            ->title('Debit Due Date Close')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body($message)
            ->badge(asset('pwa/pwa-512x512.png'))
            ->action('View', '/notifications/' . $notification->id)
            ->vibrate([500, 250, 500, 250]);
    }
}
