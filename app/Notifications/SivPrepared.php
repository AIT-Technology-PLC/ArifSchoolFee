<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SivPrepared extends Notification
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
            'message' => 'Approval request for SIV prepared by '.ucfirst($this->siv->createdBy->name),
            'endpoint' => '/sivs/'.$this->siv->id,
        ];
    }
}
