<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EarningApproved extends Notification
{
    use Queueable;

    public function __construct($earning)
    {
        $this->earning = $earning;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'fas fa-hand-holding-dollar',
            'message' => 'Earning has been approved by ' . ucfirst($this->earning->approvedBy->name),
            'endpoint' => '/earnings/' . $this->earning->id,
        ];
    }
}
