<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

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
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'project-diagram',

            'message' => 'Tender No \''.$this->tender->code.'\' status is changed from \''
            .$this->originalStatus.'\' to \''
            .$this->tender->status
            .'\' by '.$this->tender->updatedBy->name,

            'endpoint' => '/tenders/'.$this->tender->id,
        ];
    }
}
