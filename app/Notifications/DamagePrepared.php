<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DamagePrepared extends Notification
{
    use Queueable;

    public function __construct($damage)
    {
        $this->damage = $damage;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'bolt',
            'message' => 'Approval request for Damage claim prepared by '.ucfirst($this->damage->createdBy->name),
            'endpoint' => '/damages/'.$this->damage->id,
        ];
    }
}
