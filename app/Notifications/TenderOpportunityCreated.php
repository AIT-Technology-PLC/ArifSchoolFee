<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class TenderOpportunityCreated extends Notification
{
    use Queueable;

    private $tenderOpportunity;

    public function __construct($tenderOpportunity)
    {
        $this->tenderOpportunity = $tenderOpportunity;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'briefcase',
            'message' => 'New tender opportunity is created by ' . ucfirst($this->tenderOpportunity->createdBy->name),
            'endpoint' => '/tender-opportunities/' . $this->tenderOpportunity->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Tender Opportunity Created')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body('New tender opportunity is created by ' . ucfirst($this->tenderOpportunity->createdBy->name))
            ->action('View', '/notifications/' . $notification->id)
            ->vibrate([500, 250, 500, 250]);
    }
}