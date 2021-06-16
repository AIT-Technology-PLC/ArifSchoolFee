<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AdjustmentPrepared extends Notification
{
    use Queueable;

    public function __construct($adjustment)
    {
        $this->adjustment = $adjustment;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'eraser',
            'message' => 'Approval request for inventory adjustment prepared by ' . ucfirst($this->adjustment->createdBy->name),
            'endpoint' => '/adjustments/' . $this->adjustment->id,
        ];
    }
}
