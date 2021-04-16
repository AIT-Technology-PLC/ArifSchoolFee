<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class TenderDeadlineIsClose extends Notification
{
    use Queueable;

    public function __construct($tenders)
    {
        $this->tenders = $tenders;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'project-diagram',
            'message' => $this->tenders->count() . ' ' . Str::plural('tender', $this->tenders->count()) . ' ' . ($this->tenders->count() == 1 ? 'has' : 'have') . ' 5 days or less remaining to reach closing date',
            'endpoint' => '/tenders',
        ];
    }
}
