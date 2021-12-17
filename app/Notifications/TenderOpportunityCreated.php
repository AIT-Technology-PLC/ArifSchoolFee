<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

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
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'briefcase',
            'message' => 'New tender opportunity is created by ' . ucfirst($this->tenderOpportunity->createdBy->name),
            'endpoint' => '/tender-opportunities/' . $this->tenderOpportunity->id,
        ];
    }
}
