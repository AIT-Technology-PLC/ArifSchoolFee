<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SivApproved extends Notification
{
    use Queueable;

    public function __construct($siv)
    {
        $this->siv = $siv;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'file-export',
            'message' => 'SIV has been approved by ' . ucfirst($this->siv->approvedBy->name),
            'endpoint' => '/sivs/' . $this->siv->id,
        ];
    }
}
