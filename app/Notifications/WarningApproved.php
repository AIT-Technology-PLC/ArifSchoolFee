<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class WarningApproved extends Notification
{
    use Queueable;

    public function __construct($warning)
    {
        $this->warning = $warning;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'fas fa-circle-exclamation',
            'message' => 'Warning has been approved by ' . ucfirst($this->warning->approvedBy->name),
            'endpoint' => '/warnings/' . $this->warning->id,
        ];
    }
}
