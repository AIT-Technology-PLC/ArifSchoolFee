<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class TenderStatusChanged extends Notification
{
    use Queueable;

    private $tender;

    private $originalStatus;

    public function __construct($originalStatus, $tender)
    {
        $this->tender = $tender;

        $this->originalStatus = $originalStatus;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'project-diagram',

            'message' => 'Tender No \'' . $this->tender->code . '\' status is changed from \''
            . $this->originalStatus . '\' to \''
            . $this->tender->status
            . '\' by ' . $this->tender->updatedBy->name,

            'endpoint' => '/tenders/' . $this->tender->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Tender Status Changed')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body('Tender No \'' . $this->tender->code . '\' status is changed from \''
                . $this->originalStatus . '\' to \''
                . $this->tender->status
                . '\' by ' . $this->tender->updatedBy->name)
            ->badge(asset('pwa/pwa-512x512.png'))
            ->action('View', '/notifications/' . $notification->id)
            ->vibrate([500, 250, 500, 250]);
    }
}
