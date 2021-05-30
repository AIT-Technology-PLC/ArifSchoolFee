<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SivExecuted extends Notification
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
            'message' => 'SIV has been executed by ' . ucfirst($this->siv->executedBy->name),
            'endpoint' => '/sivs/' . $this->siv->id,
        ];
    }
}
