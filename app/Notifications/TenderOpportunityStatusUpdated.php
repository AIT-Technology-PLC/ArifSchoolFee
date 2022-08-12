<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class TenderOpportunityStatusUpdated extends Notification
{
    use Queueable;

    private $tenderOpportunity;

    private $originalStatus;

    public function __construct($originalStatus, $tenderOpportunity)
    {
        $this->tenderOpportunity = $tenderOpportunity;
        $this->originalStatus = $originalStatus;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'briefcase',

            'message' => 'Tender Opportunity No \'' . $this->tenderOpportunity->code . '\' status is changed from \''
            . $this->originalStatus . '\' to \''
            . $this->tenderOpportunity->tenderStatus->status
            . '\' by ' . $this->tenderOpportunity->updatedBy->name,

            'endpoint' => '/tender-opportunities/' . $this->tenderOpportunity->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Tender Opportunity Status Updated')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body('Tender Opportunity No \'' . $this->tenderOpportunity->code . '\' status is changed from \''
                . $this->originalStatus . '\' to \''
                . $this->tenderOpportunity->tenderStatus->status
                . '\' by ' . $this->tenderOpportunity->updatedBy->name)
            ->action('View', '/tender-opportunities/' . $this->tenderOpportunity->id)
            ->vibrate([500, 250, 500, 250]);
    }
}