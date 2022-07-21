<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AdvancementApproved extends Notification
{
    use Queueable;

    public function __construct($advancement)
    {
        $this->advancement = $advancement;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'fas fa-user',
            'message' => 'Advancement has been approved by ' . ucfirst($this->advancement->approvedBy->name),
            'endpoint' => '/advancements/' . $this->advancement->id,
        ];
    }
}
