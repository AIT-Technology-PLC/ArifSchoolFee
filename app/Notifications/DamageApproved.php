<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DamageApproved extends Notification
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
            'message' => 'Damage claim has been approved by ' . ucfirst($this->damage->approvedBy->name),
            'endpoint' => '/damages/' . $this->damage->id,
        ];
    }
}
