<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CompensationCreated extends Notification
{
    use Queueable;

    public function __construct($compensation)
    {
        $this->compensation = $compensation;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'fas fa-user',
            'message' => 'New compensation has been created by ' . ucfirst($this->compensation->createdBy->name),
            'endpoint' => '/compensations/' . $this->compensation->id,
        ];
    }
}