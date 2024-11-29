<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class SendNoticeMessage extends Notification
{
    use Queueable;

    private $notice;

    public function __construct($notice)
    {
        $this->notice = $notice;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'fas fa-comments',
            'message' => ucfirst($this->notice->title) . ". \n" . ucfirst(strip_tags($this->notice->message)) . "\n ( " . $this->notice->notice_date . " )". ' Prepared By ' . $this->notice->createdBy->name,
            'endpoint' => '/notifications/',
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title(ucfirst($this->notice->title))
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body(ucfirst($this->notice->message). "\n" . $this->notice->notice_date)
            ->badge(asset('pwa/pwa-512x512.png'))
            ->action('View', '/notifications/' . $notification->id)
            ->vibrate([500, 250, 500, 250]);
    }
}
