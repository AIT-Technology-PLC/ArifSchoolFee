<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DamageSubtracted extends Notification
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
            'message' => 'Damage claim products have been subtracted from inventory',
            'endpoint' => '/damages/'.$this->damage->id,
        ];
    }
}
