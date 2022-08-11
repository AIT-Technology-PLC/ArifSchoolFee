<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class BidBondDeadlineIsClose extends Notification
{
    use Queueable;

    private $totalTenders;

    public function __construct($totalTenders)
    {
        $this->totalTenders = $totalTenders;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        $message = Str::of('Bid ')
            ->append(
                Str::plural('bond', $this->totalTenders),
                ' of ',
                $this->totalTenders,
                ' ',
                Str::plural('tender', $this->totalTenders),
                ' will expire',
                ' after 5 days or less'
            );

        return [
            'icon' => 'project-diagram',
            'message' => $message,
            'endpoint' => '/tenders',
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        $message = Str::of('Bid ')
            ->append(
                Str::plural('bond', $this->totalTenders),
                ' of ',
                $this->totalTenders,
                ' ',
                Str::plural('tender', $this->totalTenders),
                ' will expire',
                ' after 5 days or less'
            );

        return (new WebPushMessage)
            ->title('Bid Bond Deadline Close')
            ->body($message)
            ->action('View', '/tenders', 'project-diagram')
            ->data(['id' => $notification->id]);
    }
}