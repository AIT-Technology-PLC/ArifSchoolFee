<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class WarningCreated extends Notification
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
            'message' => 'New warning has been created by ' . ucfirst($this->warning->createdBy->name),
            'endpoint' => '/warnings/' . $this->warning->id,
        ];
    }
}
