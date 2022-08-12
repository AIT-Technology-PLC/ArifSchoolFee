<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class CreditDueDateIsClose extends Notification
{
    use Queueable;

    private $totalCredits;

    public function __construct($totalCredits)
    {
        $this->totalCredits = $totalCredits;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        $message = Str::of($this->totalCredits)
            ->append(
                Str::plural(' credit', $this->totalCredits),
                ' will be due',
                ' after 7 days or less'
            );

        return [
            'icon' => 'fas fa-money-check',
            'message' => $message,
            'endpoint' => '/credits?type=due',
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        $message = Str::of($this->totalCredits)
            ->append(
                Str::plural(' credit', $this->totalCredits),
                ' will be due',
                ' after 7 days or less'
            );

        return (new WebPushMessage)
            ->title('Credit Due Date Close')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body($message)
            ->action('View', '/notifications/' . $notification->id)
            ->vibrate([500, 250, 500, 250]);
    }
}