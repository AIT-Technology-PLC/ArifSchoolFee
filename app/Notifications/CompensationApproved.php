<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CompensationApproved extends Notification
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
            'message' => 'New compensation is created by ' . ucfirst($this->compensation->approvedBy->name),
            'endpoint' => '/compensations/' . $this->compensation->id,
        ];
    }
}