<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class GdnApproved extends Notification
{
    use Queueable;

    public function __construct($gdn)
    {
        $this->gdn = $gdn;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'file-invoice',
            'message' => 'Delivery Order has been approved by ' . ucfirst($this->gdn->approvedBy->name),
            'endpoint' => '/gdns/' . $this->gdn->id,
        ];
    }
}
