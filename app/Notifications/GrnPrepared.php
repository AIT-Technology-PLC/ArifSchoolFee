<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class GrnPrepared extends Notification
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
            'icon' => 'file-contract',
            'message' => 'Approval request for GRN prepared by ' . ucfirst($this->grn->createdBy->name),
            'endpoint' => '/grns/' . $this->grn->id,
        ];
    }
}
