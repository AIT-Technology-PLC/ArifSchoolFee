<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class GrnApproved extends Notification
{
    use Queueable;

    public function __construct($grn)
    {
        $this->grn = $grn;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'GRN has been approved by' . ucfirst($this->grn->approvedBy->name),
            'endpoint' => '/grn/' . $this->grn->id,
        ];
    }
}
