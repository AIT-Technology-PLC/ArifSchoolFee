<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AdvancementCreated extends Notification
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
            'message' => 'New advancement has been created by ' . ucfirst($this->advancement->createdBy->name),
            'endpoint' => '/advancements/' . $this->advancement->id,
        ];
    }
}
