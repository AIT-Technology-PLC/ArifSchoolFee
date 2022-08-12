<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class TenderDeadlineIsClose extends Notification
{
    use Queueable;

    public function __construct($tenders)
    {
        $this->tenders = $tenders;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'project-diagram',
            'message' => $this->tenders->count() . ' ' . Str::plural('tender', $this->tenders->count()) . ' ' . ($this->tenders->count() == 1 ? 'has' : 'have') . ' 5 days or less remaining to reach closing date',
            'endpoint' => '/tenders',
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Tender Deadline Close')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body($this->tenders->count() . ' ' . Str::plural('tender', $this->tenders->count()) . ' ' . ($this->tenders->count() == 1 ? 'has' : 'have') . ' 5 days or less remaining to reach closing date')
            ->action('View', '/notifications/' . $notification->id)
            ->vibrate([500, 250, 500, 250]);
    }
}